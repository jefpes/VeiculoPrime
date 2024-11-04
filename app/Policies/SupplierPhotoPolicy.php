<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{SupplierPhoto, User};

class SupplierPhotoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::SUPPLIER_PHOTO_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupplierPhoto $supplierPhoto): bool
    {
        return $user->hasAbility(Permission::SUPPLIER_PHOTO_READ->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::SUPPLIER_PHOTO_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupplierPhoto $supplierPhoto): bool
    {
        return $user->hasAbility(Permission::SUPPLIER_PHOTO_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupplierPhoto $supplierPhoto): bool
    {
        return $user->hasAbility(Permission::SUPPLIER_PHOTO_DELETE->value);
    }
}
