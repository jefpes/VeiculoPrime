<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{Sale, User};

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::SALE_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): bool
    {
        return $user->hasAbility(Permission::SALE_READ->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility(Permission::SALE_CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $sale): bool
    {
        if ($sale->status === 'REEMBOLSADO' || $sale->status === 'CANCELADO') {
            return false;
        }

        return $user->hasAbility(Permission::SALE_UPDATE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function saleCancel(User $user, Sale $sale): bool
    {
        return $user->hasAbility(Permission::SALE_CANCEL->value);
    }
}
