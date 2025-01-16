<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
    public function retrieved(Role $role): void
    {
    }
    public function creating(Role $role): void
    {
    }
    public function created(Role $role): void
    {
    }
    public function updating(Role $role): void
    {
    }
    public function updated(Role $role): void
    {
    }
    public function saving(Role $role): void
    {
    }
    public function saved(Role $role): void
    {
    }
    public function deleting(Role $role): void
    {
        if ($role->users()->exists()) {
            $role->users()->detach();
        }

        if ($role->abilities()->exists()) {
            $role->abilities()->detach();
        }
    }
    public function deleted(Role $role): void
    {
    }
    public function trashed(Role $role): void
    {
    }
    public function forceDeleting(Role $role): void
    {
    }
    public function forceDeleted(Role $role): void
    {
    }
    public function restored(Role $role): void
    {
    }
    public function replicating(Role $role): void
    {
    }
}
