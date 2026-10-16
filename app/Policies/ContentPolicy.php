<?php

namespace App\Policies;

use App\Models\User;

class ContentPolicy
{
    protected function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    protected function isManager(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'administrator']);
    }

    protected function isEditor(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'administrator', 'editor']);
    }

    protected function isContributor(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'administrator', 'editor', 'kontributor']);
    }

    protected function isViewer(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'administrator', 'editor', 'kontributor', 'viewer']);
    }

    public function viewAny(User $user): bool
    {
        return $this->isViewer($user);
    }

    public function view(User $user): bool
    {
        return $this->isViewer($user);
    }

    public function create(User $user): bool
    {
        return $this->isContributor($user);
    }

    public function update(User $user): bool
    {
        return $this->isEditor($user);
    }

    public function delete(User $user): bool
    {
        return $this->isManager($user);
    }

    public function restore(User $user): bool
    {
        return $this->isManager($user);
    }

    public function forceDelete(User $user): bool
    {
        return $this->isSuperAdmin($user);
    }
}
