<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\User;
use App\Notifications\LeaveRequestStatusUpdatedNotification;
use App\Notifications\LeaveRequestSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeaveNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $hrUser;
    private User $employeeUser;
    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        Role::forceCreate(['id' => 1, 'title' => 'Super Admin', 'description' => 'Full Access']);
        Role::forceCreate(['id' => 2, 'title' => 'HR Manager', 'description' => 'HR Access']);
        Role::forceCreate(['id' => 3, 'title' => 'Employee', 'description' => 'Employee Access']);

        $department = Department::create([
            'name' => 'Engineering',
            'description' => 'Tech team',
            'status' => 'active',
        ]);

        $adminEmp = Employee::create([
            'fullname' => 'Siti Admin',
            'email' => 'admin@notif.test',
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => 1,
            'status' => 'active',
            'salary' => 10000000,
        ]);

        $this->adminUser = User::forceCreate([
            'name' => $adminEmp->fullname,
            'email' => $adminEmp->email,
            'password' => Hash::make('password'),
            'role_id' => 1,
            'employee_id' => $adminEmp->id,
        ]);

        $hrEmp = Employee::create([
            'fullname' => 'Budi HR',
            'email' => 'hr@notif.test',
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => 2,
            'status' => 'active',
            'salary' => 8000000,
        ]);

        $this->hrUser = User::forceCreate([
            'name' => $hrEmp->fullname,
            'email' => $hrEmp->email,
            'password' => Hash::make('password'),
            'role_id' => 2,
            'employee_id' => $hrEmp->id,
        ]);

        $this->employee = Employee::create([
            'fullname' => 'Rina Staff',
            'email' => 'rina@notif.test',
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => 3,
            'status' => 'active',
            'salary' => 5000000,
        ]);

        $this->employeeUser = User::forceCreate([
            'name' => $this->employee->fullname,
            'email' => $this->employee->email,
            'password' => Hash::make('password'),
            'role_id' => 3,
            'employee_id' => $this->employee->id,
        ]);
    }

    public function test_notifications_implement_should_queue(): void
    {
        $leave = LeaveRequest::create([
            'employee_id' => $this->employee->id,
            'reason' => 'Annual leave',
            'start_date' => '2026-12-10',
            'end_date' => '2026-12-12',
            'status' => 'pending',
        ]);

        $this->assertInstanceOf(ShouldQueue::class, new LeaveRequestSubmittedNotification($leave));
        $this->assertInstanceOf(ShouldQueue::class, new LeaveRequestStatusUpdatedNotification($leave));
    }

    public function test_submitting_leave_request_via_web_notifies_hr_and_admin(): void
    {
        Notification::fake();

        $this->actingAs($this->employeeUser)
            ->post(route('leave-requests.store'), [
                'reason' => 'Family trip',
                'start_date' => '2026-12-15',
                'end_date' => '2026-12-17',
            ])
            ->assertRedirect(route('leave-requests.index'));

        Notification::assertSentTo(
            [$this->adminUser, $this->hrUser],
            LeaveRequestSubmittedNotification::class,
            function (LeaveRequestSubmittedNotification $notification) {
                return $notification->leaveRequest->employee_id === $this->employee->id;
            }
        );

        Notification::assertNotSentTo($this->employeeUser, LeaveRequestSubmittedNotification::class);
    }

    public function test_approving_leave_request_via_web_notifies_employee(): void
    {
        Notification::fake();

        $leave = LeaveRequest::create([
            'employee_id' => $this->employee->id,
            'reason' => 'Medical checkup',
            'start_date' => '2026-12-20',
            'end_date' => '2026-12-21',
            'status' => 'pending',
        ]);

        $this->actingAs($this->hrUser)
            ->get(route('leave-requests.confirm', $leave->id))
            ->assertRedirect(route('leave-requests.index'));

        Notification::assertSentTo(
            $this->employeeUser,
            LeaveRequestStatusUpdatedNotification::class,
            function (LeaveRequestStatusUpdatedNotification $notification) use ($leave) {
                $mail = $notification->toMail($this->employeeUser);

                return $notification->leaveRequest->id === $leave->id
                    && $notification->leaveRequest->status === 'approved'
                    && str_contains($mail->subject, 'Approved');
            }
        );
    }

    public function test_rejecting_leave_request_via_web_notifies_employee(): void
    {
        Notification::fake();

        $leave = LeaveRequest::create([
            'employee_id' => $this->employee->id,
            'reason' => 'Personal errand',
            'start_date' => '2026-12-22',
            'end_date' => '2026-12-23',
            'status' => 'pending',
        ]);

        $this->actingAs($this->hrUser)
            ->get(route('leave-requests.reject', $leave->id))
            ->assertRedirect(route('leave-requests.index'));

        Notification::assertSentTo(
            $this->employeeUser,
            LeaveRequestStatusUpdatedNotification::class,
            function (LeaveRequestStatusUpdatedNotification $notification) use ($leave) {
                $mail = $notification->toMail($this->employeeUser);

                return $notification->leaveRequest->id === $leave->id
                    && $notification->leaveRequest->status === 'rejected'
                    && str_contains($mail->subject, 'Rejected');
            }
        );
    }

    public function test_api_leave_submission_and_approval_trigger_notifications(): void
    {
        Notification::fake();

        // 1. Employee submits via API -> notifies HR & Admin
        Sanctum::actingAs($this->employeeUser);
        $response = $this->postJson('/api/v1/leave-requests', [
            'reason' => 'Year-end holiday',
            'start_date' => '2026-12-26',
            'end_date' => '2026-12-28',
        ])->assertCreated();

        $leaveId = $response->json('data.id');

        Notification::assertSentTo([$this->adminUser, $this->hrUser], LeaveRequestSubmittedNotification::class);

        // 2. HR approves via API -> notifies Employee
        Sanctum::actingAs($this->hrUser);
        $this->patchJson("/api/v1/leave-requests/{$leaveId}/status", [
            'status' => 'approved',
        ])->assertOk();

        Notification::assertSentTo($this->employeeUser, LeaveRequestStatusUpdatedNotification::class);
    }
}