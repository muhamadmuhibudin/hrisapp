<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payroll;

class PayrollPolicy
{
    /**
     * Perform pre-authorization checks.
     * Super Admin bypasses all specific policy checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return null; // Continue to the specific method if not Super Admin
    }

    public function viewAny(User $user): bool
    {
        return $this->isHR($user) || $this->isEmployee($user);
    }

    public function view(User $user, Payroll $payroll): bool
    {
        // HR can view all, Employees can only view their own payroll slip
        return $this->isHR($user) || (int) $user->employee_id === (int) $payroll->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isHR($user);
    }

    public function update(User $user, Payroll $payroll): bool
    {
        return $this->isHR($user);
    }

    public function delete(User $user, Payroll $payroll): bool
    {
        return $this->isHR($user);
    }

    // --- Role Helper Methods ---

    private function isSuperAdmin(User $user): bool
    {
        return (int) $user->role_id === 1;
    }

    private function isHR(User $user): bool
    {
        return (int) $user->role_id === 2;
    }

    private function isEmployee(User $user): bool
    {
        return (int) $user->role_id === 3;
    }
}