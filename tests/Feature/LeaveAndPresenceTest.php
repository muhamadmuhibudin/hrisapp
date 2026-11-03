<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Presence;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LeaveAndPresenceTest extends TestCase
{
    use RefreshDatabase;

    private Employee $employee;
    private User $employeeUser;

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

        $this->employee = Employee::create([
            'fullname' => 'Deni Employee',
            'email' => 'deni@test.com',
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

    public function test_employee_can_create_leave_request(): void
    {
        $response = $this->actingAs($this->employeeUser)
            ->post(route('leave-requests.store'), [
                'employee_id' => $this->employee->id,
                'reason'      => 'Medical checkup',
                'start_date'  => now()->addDays(2)->toDateString(),
                'end_date'    => now()->addDays(3)->toDateString(),
            ]);

        $response->assertRedirect(route('leave-requests.index'));

        $this->assertDatabaseHas('leave_requests', [
            'employee_id' => $this->employee->id,
            'reason'      => 'Medical checkup',
            'status'      => 'pending',
        ]);
    }

    public function test_employee_cannot_edit_processed_leave_request(): void
    {
        $leave = LeaveRequest::create([
            'employee_id' => $this->employee->id,
            'reason'      => 'Family vacation',
            'start_date'  => now()->addDays(5)->toDateString(),
            'end_date'    => now()->addDays(6)->toDateString(),
            'status'      => 'confirmed',
        ]);

        $this->actingAs($this->employeeUser)
            ->get(route('leave-requests.edit', $leave))
            ->assertForbidden();

        $this->actingAs($this->employeeUser)
            ->put(route('leave-requests.update', $leave), [
                'employee_id' => $this->employee->id,
                'reason'      => 'Updated reason',
                'start_date'  => now()->addDays(5)->toDateString(),
                'end_date'    => now()->addDays(6)->toDateString(),
            ])
            ->assertForbidden();
    }

    public function test_employee_cannot_check_in_twice_on_same_day(): void
    {
        $today = now()->toDateString();

        Presence::create([
            'employee_id' => $this->employee->id,
            'date' => $today,
            'check_in' => '08:00:00',
            'status' => 'present',
        ]);

        $response = $this->actingAs($this->employeeUser)
            ->post(route('presences.store'), [
                'employee_id' => $this->employee->id,
                'date' => $today,
                'check_in' => '09:00:00',
                'status' => 'present',
            ]);

        $this->assertDatabaseCount('presences', 1);
    }
}