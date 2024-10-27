<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{PaymentInstallments, User};

class PaymentInstallmentsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility(Permission::INSTALLMENT_READ->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentInstallments $paymentInstallments): bool
    {
        return $user->hasAbility(Permission::INSTALLMENT_READ->value);
    }

    /**
     * Determine whether the user can receive payment.
     */
    public function receive(User $user, PaymentInstallments $paymentInstallments): bool
    {
        if ($paymentInstallments->status !== 'PENDENTE') {
            return false;
        }

        return $user->hasAbility(Permission::PAYMENT_RECEIVE->value);
    }

    /**
     * Determine whether the user can refund payment.
     */
    public function refund(User $user, PaymentInstallments $paymentInstallments): bool
    {
        if ($paymentInstallments->status !== 'PAGO') {
            return false;
        }

        return $user->hasAbility(Permission::PAYMENT_UNDO->value);
    }

    /**
     * Determine whether the user can payment receipt.
     */
    public function receipt(User $user, PaymentInstallments $paymentInstallments): bool
    {
        if ($paymentInstallments->status !== 'PAGO') {
            return false;
        }

        return $user->hasAbility(Permission::PAYMENT_UNDO->value);
    }
}
