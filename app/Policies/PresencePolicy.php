<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Presence;

class PresencePolicy
{
    private function isSuperAdmin(User $user): bool
    {
        return $user->role_id === 1;
    }

    private function isHR(User $user): bool
    {
        return $user->role_id === 2;
    }

    private function isEmployee(User $user): bool
    {
        return $user->role_id === 3;
    }

    public function viewAny(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || $this->isEmployee($user);
    }

    public function view(User $user, Presence $presence): bool
    {
        return $this->isSuperAdmin($user)
            || $this->isHR($user)
            || (int) $user->employee_id === (int) $presence->employee_id;
    }

    public function create(User $user): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user) || $this->isEmployee($user);
    }

    public function update(User $user, Presence $presence): bool
    {
        return $this->isSuperAdmin($user)
            || $this->isHR($user)
            || (int) $user->employee_id === (int) $presence->employee_id;
    }

    public function delete(User $user, Presence $presence): bool
    {
        return $this->isSuperAdmin($user) || $this->isHR($user);
    }

    public function restore(User $user, Presence $presence): bool
    {
        return $this->isSuperAdmin($user);
    }

    public function forceDelete(User $user, Presence $presence): bool
    {
        return $this->isSuperAdmin($user);
    }
}