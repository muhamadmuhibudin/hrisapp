<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LeaveApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $hrUser;
    private User $employeeUser;
    private LeaveRequest $pendingLeave;

    protected function setUp(): void
    {
        parent::setUp();

        Role::forceCreate(['id' => 1, 'title' => 'Super Admin', 'description' => 'Full Access']);
        Role::forceCreate(['id' => 2, 'title' => 'HR Manager', 'description' => 'HR Access']);
        Role::forceCreate(['id' => 3, 'title' => 'Employee', 'description' => 'Employee Access']);

        $department = Department::create([
            'name' => 'Operations',
            'description' => 'Ops team',
            'status' => 'active',
        ]);

        $hrEmployee = Employee::create([
            'fullname' => 'Budi HR',
            'email' => 'hr@workflow.test',
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => 2,
            'status' => 'active',
            'salary' => 8000000,
        ]);

        $this->hrUser = User::forceCreate([
            'name' => $hrEmployee->fullname,
            'email' => $hrEmployee->email,
            'password' => Hash::make('password'),
            'role_id' => 2,
            'employee_id' => $hrEmployee->id,
        ]);

        $employee = Employee::create([
            'fullname' => 'Rina Staff',
            'email' => 'rina@workflow.test',
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => 3,
            'status' => 'active',
            'salary' => 5000000,
        ]);

        $this->employeeUser = User::forceCreate([
            'name' => $employee->fullname,
            'email' => $employee->email,
            'password' => Hash::make('password'),
            'role_id' => 3,
            'employee_id' => $employee->id,
        ]);

        $this->pendingLeave = LeaveRequest::create([
            'employee_id' => $employee->id,
            'reason' => 'Family event',
            'start_date' => '2026-12-10',
            'end_date' => '2026-12-12',
            'status' => 'pending',
        ]);
    }

    public function test_hr_can_approve_leave(): void
    {
        $response = $this->actingAs($this->hrUser)
            ->get(route('leave-requests.confirm', $this->pendingLeave->id));

        $response->assertRedirect(route('leave-requests.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('leave_requests', [
            'id' => $this->pendingLeave->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'leave.approved',
            'subject_id' => $this->pendingLeave->id,
        ]);

        $this->actingAs($this->hrUser)
            ->get(route('leave-requests.index'))
            ->assertOk()
            ->assertSee('Approved by: ' . $this->hrUser->name);
    }

    public function test_hr_can_reject_leave(): void
    {
        $response = $this->actingAs($this->hrUser)
            ->get(route('leave-requests.reject', $this->pendingLeave->id));

        $response->assertRedirect(route('leave-requests.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('leave_requests', [
            'id' => $this->pendingLeave->id,
            'status' => 'rejected',
            'rejected_by' => $this->hrUser->id,
        ]);

        $this->assertNotNull($this->pendingLeave->fresh()->rejected_at);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'leave.rejected',
            'subject_id' => $this->pendingLeave->id,
        ]);
    }

    public function test_employee_cannot_approve_leave(): void
    {
        $response = $this->actingAs($this->employeeUser)
            ->get(route('leave-requests.confirm', $this->pendingLeave->id));

        $response->assertForbidden();

        $this->assertDatabaseHas('leave_requests', [
            'id' => $this->pendingLeave->id,
            'status' => 'pending',
        ]);
    }

    public function test_approved_leave_cannot_be_approved_twice(): void
    {
        // First approval succeeds
        $this->actingAs($this->hrUser)
            ->get(route('leave-requests.confirm', $this->pendingLeave->id))
            ->assertSessionHas('success');

        $firstApprovedAt = $this->pendingLeave->fresh()->approved_at;

        // Second approval attempt is blocked
        $response = $this->actingAs($this->hrUser)
            ->get(route('leave-requests.confirm', $this->pendingLeave->id));

        $response->assertRedirect(route('leave-requests.index'))
            ->assertSessionHas('error', 'This leave request has already been processed.');

        $this->assertEquals($firstApprovedAt, $this->pendingLeave->fresh()->approved_at);
    }

    public function test_approval_stores_approver_information(): void
    {
        $this->actingAs($this->hrUser)
            ->get(route('leave-requests.confirm', $this->pendingLeave->id));

        $updatedLeave = $this->pendingLeave->fresh();

        $this->assertSame($this->hrUser->id, $updatedLeave->approved_by);
        $this->assertNotNull($updatedLeave->approved_at);
        $this->assertSame($this->hrUser->name, $updatedLeave->approvedBy->name);
        $this->assertNull($updatedLeave->rejected_by);
        $this->assertNull($updatedLeave->rejected_at);
    }
}