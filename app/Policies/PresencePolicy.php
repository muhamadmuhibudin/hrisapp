<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;

class PresencePolicy
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
        return $this->isHR($user) || $this->isEmployee($user);
    }

    public function view(User $user, Presence $presence): bool
    {
        // HR can view all presence records, Employees can only view their own
        return $this->isHR($user) || (int) $user->employee_id === (int) $presence->employee_id;
    }

    public function create(User $user): bool
    {
        // HR and Employees can clock in/out
        return $this->isHR($user) || $this->isEmployee($user);
    }

    public function update(User $user, Presence $presence): bool
    {
        // HR can update any record, Employees can only update their own
        return $this->isHR($user) || (int) $user->employee_id === (int) $presence->employee_id;
    }

    public function delete(User $user, Presence $presence): bool
    {
        // Only HR and Super Admin (via before method) can delete presence records
        return $this->isHR($user);
    }

    public function restore(User $user, Presence $presence): bool
    {
        return false; // Handled by before() method
    }

    public function forceDelete(User $user, Presence $presence): bool
    {
        return false; // Handled by before() method
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