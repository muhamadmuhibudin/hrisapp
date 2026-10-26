<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
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

    public function view(User $user, Department $department): bool
    {
        return $this->isHR($user);
    }

    public function create(User $user): bool
    {
        return $this->isHR($user);
    }

    public function update(User $user, Department $department): bool
    {
        return $this->isHR($user);
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->isHR($user);
    }

    public function restore(User $user, Department $department): bool
    {
        return false;
    }

    public function forceDelete(User $user, Department $department): bool
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
}