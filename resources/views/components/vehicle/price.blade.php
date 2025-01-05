@props(['vehicle', 'paymentMethods'])

<div class="border-b py-4 border-[var(--f-text-variant-5)] flex justify-between items-center"
     x-data="{modalIsOpen: false}">
    <div>
        @if($vehicle->promotional_price)
            <p class="text-[var(--f-text-variant-6)] line-through text-sm">
                R$ {{ number_format($vehicle->promotional_price, 2, ',', '.') }}
            </p>
        @endif
        <p class="text-[var(--f-secondary-color)] text-3xl font-bold">
            R$ {{ number_format($vehicle->sale_price, 2, ',', '.') }}
        </p>

        <button @click="modalIsOpen = true"
                class="font-medium text-black underline-offset-2 underline focus:outline-none mt-2">
            {{ trans('Payment methods') }}
        </button>

        <x-vehicle.payment-methods :methods="$paymentMethods"/>
    </div>
    <div>
        <button class="bg-[var(--f-secondary-color)] text-[var(--f-text-variant-1)] font-semibold px-6 py-2 rounded-lg focus:outline-none">
            {{ trans('Buy') }}
        </button>
    </div>
</div>
