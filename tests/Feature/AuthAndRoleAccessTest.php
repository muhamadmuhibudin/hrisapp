<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::forceCreate(['id' => 1, 'title' => 'Super Admin', 'description' => 'Full Access']);
        Role::forceCreate(['id' => 2, 'title' => 'HR Manager', 'description' => 'HR Access']);
        Role::forceCreate(['id' => 3, 'title' => 'Employee', 'description' => 'Employee Access']);
    }

    private function createUserWithRole(int $roleId, string $email): User
    {
        $department = Department::create([
            'name' => 'Engineering',
            'description' => 'Tech team',
            'status' => 'active',
        ]);

        $employee = Employee::create([
            'fullname' => 'Test User ' . $roleId,
            'email' => $email,
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => $roleId,
            'status' => 'active',
            'salary' => 5000000,
        ]);

        return User::forceCreate([
            'name' => $employee->fullname,
            'email' => $email,
            'password' => Hash::make('password'),
            'role_id' => $roleId,
            'employee_id' => $employee->id,
        ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = $this->createUserWithRole(3, 'employee@test.com');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = $this->createUserWithRole(3, 'employee@test.com');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_employee_cannot_access_admin_routes(): void
    {
        $employee = $this->createUserWithRole(3, 'employee@test.com');

        $this->actingAs($employee)
            ->get(route('activity-logs.index'))
            ->assertForbidden();

        $this->actingAs($employee)
            ->get(route('roles.index'))
            ->assertForbidden();
    }

    public function test_hr_can_access_payroll_page(): void
    {
        $hr = $this->createUserWithRole(2, 'hr@test.com');

        $this->actingAs($hr)
            ->get(route('payrolls.index'))
            ->assertOk();
    }
}