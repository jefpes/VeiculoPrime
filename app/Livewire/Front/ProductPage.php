<?php

namespace App\Livewire\Front;

use Livewire\Component;

class ProductPage extends Component
{
    public mixed $product;

    public mixed $paymentMethods;

    public mixed $similarProducts;

    public function mount()
    {
        $this->product        = (object) $this->product;
        $this->paymentMethods = collect($this->paymentMethods)->map(function ($paymentMethod) {
            return (object) $paymentMethod;
        })->toArray();

        $this->similarProducts = collect($this->products)->map(function ($product) {
            return (object) $product;
        })->toArray();
    }
}
