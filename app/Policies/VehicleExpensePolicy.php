<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{User, VehicleExpense};

class VehicleExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::VEHICLE_EXPENSE_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleExpense $vehicleExpense): bool
    {
        return $user->hasAbility(Permission::VEHICLE_EXPENSE_READ->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::VEHICLE_EXPENSE_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleExpense $vehicleExpense): bool
    {
        return $user->hasAbility(Permission::VEHICLE_EXPENSE_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleExpense $vehicleExpense): bool
    {
        return $user->hasAbility(Permission::VEHICLE_EXPENSE_DELETE->value);
    }
}
