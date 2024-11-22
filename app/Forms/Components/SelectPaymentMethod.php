<?php

namespace App\Forms\Components;

use App\Enums\PaymentMethod;
use Filament\Forms\Components\{Select};

class SelectPaymentMethod extends Select
{
    public function setUp(): void
    {
        parent::setUp();

        $this->options(
            collect(PaymentMethod::cases())
                                ->mapWithKeys(fn (PaymentMethod $type) => [$type->value => ucfirst($type->value)])
        );
    }
}
