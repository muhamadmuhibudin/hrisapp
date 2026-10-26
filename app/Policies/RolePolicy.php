<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
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

    public function view(User $user, Role $role): bool
    {
        return $this->isHR($user);
    }

    public function create(User $user): bool
    {
        return $this->isHR($user);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->isHR($user);
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->isHR($user);
    }

    public function restore(User $user, Role $role): bool
    {
        // Handled by before() method. Only Super Admin can restore.
        return false;
    }

    public function forceDelete(User $user, Role $role): bool
    {
        // Handled by before() method. Only Super Admin can force delete.
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