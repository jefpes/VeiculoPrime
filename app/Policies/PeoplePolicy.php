<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{People, User};

class PeoplePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::PEOPLE_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, People $people): bool
    {
        return $user->hasAbility(Permission::PEOPLE_READ->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::PEOPLE_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, People $people): bool
    {
        return $user->hasAbility(Permission::PEOPLE_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, People $people): bool
    {
        return $user->hasAbility(Permission::PEOPLE_DELETE->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function attachUser(User $user, People $people): bool
    {
        return $user->hasAbility(Permission::USER_CREATE->value);
    }
}
