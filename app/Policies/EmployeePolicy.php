<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
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

        return null; 
    }

    public function viewAny(User $user): bool
    {
        return $this->isHR($user);
    }

    public function view(User $user, Employee $employee): bool
    {
        // HR can view any profile, Employees can only view their own profile
        return $this->isHR($user) || (int) $user->employee_id === (int) $employee->id;
    }

    public function create(User $user): bool
    {
        return $this->isHR($user);
    }

    public function update(User $user, Employee $employee): bool
    {
        // HR can update any profile, Employees can only update their own profile
        return $this->isHR($user) || (int) $user->employee_id === (int) $employee->id;
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $this->isHR($user);
    }

    public function restore(User $user, Employee $employee): bool
    {
        return false;
    }

    public function forceDelete(User $user, Employee $employee): bool
    {
        return false;
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