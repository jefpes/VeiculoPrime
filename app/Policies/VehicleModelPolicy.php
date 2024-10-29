<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{User, VehicleModel};

class VehicleModelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::VEHICLE_MODEL_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleModel $vehicleModel): bool
    {
        return $user->hasAbility(Permission::VEHICLE_MODEL_READ->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::VEHICLE_MODEL_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleModel $vehicleModel): bool
    {
        return $user->hasAbility(Permission::VEHICLE_MODEL_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleModel $vehicleModel): bool
    {
        return $user->hasAbility(Permission::VEHICLE_MODEL_DELETE->value);
    }
}
