<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    private function isSuperAdmin(User $user): bool
    {
        return $user->role_id === 1;
    }

    private function isHR(User $user): bool
    {
        return $user->role_id === 2;
    }

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function view(User $user, Department $department): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function update(User $user, Department $department): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function restore(User $user, Department $department): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function forceDelete(User $user, Department $department): bool
    {
        return $this->isSuperAdmin($user);
    }
}