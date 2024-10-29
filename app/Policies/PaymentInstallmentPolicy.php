<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\{PaymentInstallment, User};

class PaymentInstallmentPolicy
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
    public function view(User $user, PaymentInstallment $paymentInstallment): bool
    {
        return $user->hasAbility(Permission::INSTALLMENT_READ->value);
    }

    /**
     * Determine whether the user can receive payment.
     */
    public function receive(User $user, PaymentInstallment $paymentInstallment): bool
    {
        if ($paymentInstallment->status !== 'PENDENTE') {
            return false;
        }

        return $user->hasAbility(Permission::PAYMENT_RECEIVE->value);
    }

    /**
     * Determine whether the user can refund payment.
     */
    public function refund(User $user, PaymentInstallment $paymentInstallment): bool
    {
        if ($paymentInstallment->status !== 'PAGO') {
            return false;
        }

        return $user->hasAbility(Permission::PAYMENT_UNDO->value);
    }

    /**
     * Determine whether the user can payment receipt.
     */
    public function receipt(User $user, PaymentInstallment $paymentInstallment): bool
    {
        if ($paymentInstallment->status !== 'PAGO') {
            return false;
        }

        return $user->hasAbility(Permission::PAYMENT_UNDO->value);
    }
}
