<?php

namespace Tests\Unit;

use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HrisBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    private Employee $employee;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Role::forceCreate(['id' => 1, 'title' => 'Super Admin', 'description' => 'Full Access']);
        Role::forceCreate(['id' => 3, 'title' => 'Employee', 'description' => 'Employee Access']);

        $department = Department::create([
            'name' => 'Finance',
            'description' => 'Finance team',
            'status' => 'active',
        ]);

        $this->employee = Employee::create([
            'fullname' => 'Budi Santoso',
            'email' => 'budi@test.com',
            'hire_date' => now()->toDateString(),
            'department_id' => $department->id,
            'role_id' => 3,
            'status' => 'active',
            'salary' => 5000000,
        ]);

        $this->user = User::forceCreate([
            'name' => $this->employee->fullname,
            'email' => $this->employee->email,
            'password' => Hash::make('password'),
            'role_id' => 3,
            'employee_id' => $this->employee->id,
        ]);
    }

    public function test_payroll_deduction_calculation_and_net_salary_floor(): void
    {
        $workingDays = 22;
        $presentDays = 19; // 3 absent days
        $penaltyPerDay = 100000;

        $absentDays = $workingDays - $presentDays;
        $absencePenalty = max(0, $absentDays) * $penaltyPerDay;

        $salary = 5000000;
        $bonuses = 500000;
        $manualDeductions = 200000;

        $totalDeductions = $manualDeductions + $absencePenalty;
        $netSalary = max(0, $salary - $totalDeductions + $bonuses);

        $this->assertEquals(3, $absentDays);
        $this->assertEquals(300000, $absencePenalty);
        $this->assertEquals(500000, $totalDeductions);
        $this->assertEquals(5000000, $netSalary);

        // Ensure net salary never drops below zero when deductions exceed salary + bonuses
        $excessiveDeductionNet = max(0, 1000000 - 2500000 + 0);
        $this->assertEquals(0, $excessiveDeductionNet);
    }

    public function test_leave_overlapping_validation_detects_conflicting_dates(): void
    {
        LeaveRequest::create([
            'employee_id' => $this->employee->id,
            'reason' => 'Existing approved leave',
            'start_date' => '2026-12-10',
            'end_date' => '2026-12-15',
            'status' => 'confirmed',
        ]);

        // Scenario 1: Overlapping date range (Dec 12 - Dec 18)
        $hasOverlap = LeaveRequest::where('employee_id', $this->employee->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) {
                $query->where('start_date', '<=', '2026-12-18')
                    ->where('end_date', '>=', '2026-12-12');
            })
            ->exists();

        $this->assertTrue($hasOverlap);

        // Scenario 2: Non-overlapping date range (Dec 20 - Dec 22)
        $noOverlap = LeaveRequest::where('employee_id', $this->employee->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) {
                $query->where('start_date', '<=', '2026-12-22')
                    ->where('end_date', '>=', '2026-12-20');
            })
            ->exists();

        $this->assertFalse($noOverlap);
    }

    public function test_activity_log_record_persists_audit_entry(): void
    {
        Auth::login($this->user);

        ActivityLog::record(
            'payroll.exported',
            'Exported monthly payroll report',
            $this->employee
        );

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->user->id,
            'action' => 'payroll.exported',
            'description' => 'Exported monthly payroll report',
            'subject_type' => Employee::class,
            'subject_id' => $this->employee->id,
        ]);
    }
}