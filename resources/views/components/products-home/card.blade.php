@props([
    'product' => []
])

<div class="">
    <div class="max-w-md bg-[var(--f-background-variant-1)] border border-[var(--f-text-variant-2)] rounded-lg shadow h-full">
        <a href="#">
            @php
                $photo = $product->mainPhoto?->path;

                if(!$photo) $photo = $product->photos->first()?->path;
            @endphp
            <img class="object-cover object-center w-full h-64 rounded-t-lg" src="{{ $photo }}" alt=""/>
        </a>

        <div class="px-5 py-1">
            <a href="#" class="mb-4">
                <h4 class="flex flex-col mb-2 text-md font-bold tracking-tight text-[var(--f-text-variant-5)] ">
                    <span class="text-[var(--f-secondary-color)]">{{ strtoupper($product->model->brand->name) }}</span>
                    <strong class="-mt-2">
                        {{strtoupper($product->model->name)}}
                    </strong>
                </h4>

                <p class="mb-2">
                    <span class="text-[var(--f-text-variant-6)] border-r border-[var(--f-text-variant-3)] pr-2">{{ $product->year_one }} / {{ $product->year_two }}</span>
                    <span class="text-[var(--f-text-variant-6)] pl-2">{{ $product->km }} km</span>
                </p>

                <p class="text-[var(--f-text-variant-6)] line-through text-sm">
                    R$ {{ number_format($product->sale_price * 1.2, 2, ',', '.') }}
                </p>
                <p class="text-[var(--f-secondary-color)] text-lg font-bold">
                    R$ {{ number_format($product->promotional_price ?? $product->sale_price, 2, ',', '.') }}
                </p>

                <p class="mt-2 bg-[#f2f2f2] p-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="M12 12c-1.1 0-2-.9-2-2s.9-2 2-2s2 .9 2 2s-.9 2-2 2m6-1.8C18 6.57 15.35 4 12 4s-6 2.57-6 6.2c0 2.34 1.95 5.44 6 9.14c4.05-3.7 6-6.8 6-9.14M12 2c4.2 0 8 3.22 8 8.2c0 3.32-2.67 7.25-8 11.8c-5.33-4.55-8-8.48-8-11.8C4 5.22 7.8 2 12 2"/>
                    </svg>
                    <span class="text-[var(--f-text-variant-6)]">
                        {{ $product->store->city }} - {{ $product->store->state }}
                    </span>
                </p>
            </a>
        </div>
    </div>

</div>
