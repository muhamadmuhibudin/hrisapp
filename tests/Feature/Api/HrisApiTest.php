<?php

namespace Tests\Feature\Api;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HrisApiTest extends TestCase
{
    use RefreshDatabase;

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

        $hrEmployee = Employee::create([
            'fullname' => 'Siti HR',
            'email' => 'hr@api.test',
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

        $this->employee = Employee::create([
            'fullname' => 'Andi Employee',
            'email' => 'andi@api.test',
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

    public function test_user_can_login_fetch_profile_and_logout_via_api(): void
    {
        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => $this->employeeUser->email,
            'password' => 'password',
            'device_name' => 'phpunit',
        ]);

        $loginResponse->assertOk()
            ->assertJsonStructure([
                'message',
                'data' => ['token', 'type', 'user' => ['id', 'name', 'email', 'role_id', 'employee_id']],
            ]);

        $token = $loginResponse->json('data.token');

        $this->withToken($token)
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonPath('data.email', $this->employeeUser->email);

        $this->withToken($token)
            ->postJson('/api/v1/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logged out successfully.');
    }

    public function test_employees_endpoint_enforces_hr_and_admin_rbac(): void
    {
        Sanctum::actingAs($this->employeeUser);
        $this->getJson('/api/v1/employees')->assertForbidden();

        Sanctum::actingAs($this->hrUser);
        $this->getJson('/api/v1/employees')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_employee_can_check_in_once_per_day_via_api(): void
    {
        Sanctum::actingAs($this->employeeUser);

        // First check-in succeeds
        $this->postJson('/api/v1/presences/check-in')
            ->assertCreated()
            ->assertJsonPath('data.status', 'present');

        // Duplicate check-in on the same day is rejected
        $this->postJson('/api/v1/presences/check-in')
            ->assertUnprocessable()
            ->assertJsonPath('message', 'You have already checked in today.');
    }

    public function test_leave_request_submission_overlap_check_and_hr_approval_via_api(): void
    {
        Sanctum::actingAs($this->employeeUser);

        // Create initial leave request
        $createResponse = $this->postJson('/api/v1/leave-requests', [
            'reason' => 'Family trip',
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('data.status', 'pending');

        $leaveId = $createResponse->json('data.id');

        // Overlapping dates are rejected
        $this->postJson('/api/v1/leave-requests', [
            'reason' => 'Overlapping request',
            'start_date' => '2026-12-02',
            'end_date' => '2026-12-04',
        ])->assertUnprocessable();

        // Employee cannot approve their own leave request
        $this->patchJson("/api/v1/leave-requests/{$leaveId}/status", [
            'status' => 'approved',
        ])->assertForbidden();

        // HR Manager can approve the leave request
        Sanctum::actingAs($this->hrUser);
        $this->patchJson("/api/v1/leave-requests/{$leaveId}/status", [
            'status' => 'approved',
        ])
            ->assertOk()
            ->assertJsonPath('data.status', 'approved')
            ->assertJsonPath('data.approved_by', $this->hrUser->name);

        // Already processed leave request cannot be approved twice
        $this->patchJson("/api/v1/leave-requests/{$leaveId}/status", [
            'status' => 'approved',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'This leave request has already been processed.');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'leave.approved',
            'subject_id' => $leaveId,
        ]);
    }

    public function test_employee_can_only_view_their_own_payrolls_via_api(): void
    {
        $ownPayroll = Payroll::create([
            'employee_id' => $this->employee->id,
            'salary' => 5000000,
            'bonuses' => 250000,
            'deductions' => 100000,
            'net_salary' => 5150000,
            'pay_date' => now()->toDateString(),
        ]);

        $otherPayroll = Payroll::create([
            'employee_id' => $this->hrUser->employee_id,
            'salary' => 8000000,
            'bonuses' => 0,
            'deductions' => 0,
            'net_salary' => 8000000,
            'pay_date' => now()->toDateString(),
        ]);

        Sanctum::actingAs($this->employeeUser);

        $this->getJson('/api/v1/payrolls')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->getJson("/api/v1/payrolls/{$ownPayroll->id}")
            ->assertOk()
            ->assertJsonPath('data.net_salary', 5150000);

        $this->getJson("/api/v1/payrolls/{$otherPayroll->id}")
            ->assertForbidden();
    }
}