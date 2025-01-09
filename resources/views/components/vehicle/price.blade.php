@props(['vehicle', 'paymentMethods'])

<div class="border-b py-4 border-[var(--f-text-variant-5)] flex justify-between items-center"
     x-data="{modalIsOpen: false}">
    <div>
        @if($vehicle->promotional_price && $vehicle->promotional_price != '0.00')
            <div class="flex gap-x-2">
                <p class="text-[var(--f-text-variant-6)] line-through" >
                    R$ {{ number_format($vehicle->sale_price, 2, ',', '.') }}
                </p>

                <p class="text-2xl font-bold text-[var(--f-secondary-color)]">
                    R$ {{ number_format($vehicle->promotional_price, 2, ',', '.') }}
                </p>
            </div>
        @else
            <p class="text-2xl font-bold text-[var(--f-text-variant-6)]">
                R$ {{ number_format($vehicle->sale_price, 2, ',', '.') }}
            </p>
        @endif

        <button @click="modalIsOpen = true"
                class="font-medium text-black underline-offset-2 underline focus:outline-none mt-2">
            {{ trans('Payment methods') }}
        </button>

        <x-vehicle.payment-methods :methods="$paymentMethods"/>
    </div>
    <div>
        <a href="{{ $vehicle->store->phones->random()->gerarLinkWhatsApp() }}" target="_blank" class="bg-[var(--f-secondary-color)] text-[var(--f-text-variant-1)] font-semibold px-6 py-2 rounded-lg focus:outline-none">
            {{ trans('Buy') }}
        </a>
    </div>
</div>
