<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{ClientPhoto, User};

class ClientPhotoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::CLIENT_PHOTO_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientPhoto $clientPhoto): bool
    {
        return $user->hasAbility(Permission::CLIENT_PHOTO_READ->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::CLIENT_PHOTO_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClientPhoto $clientPhoto): bool
    {
        return $user->hasAbility(Permission::CLIENT_PHOTO_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientPhoto $clientPhoto): bool
    {
        return $user->hasAbility(Permission::CLIENT_PHOTO_DELETE->value);
    }
}
