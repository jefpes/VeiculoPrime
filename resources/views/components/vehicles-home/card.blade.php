@props([
    'vehicle' => []
])

<div class="h-full">
    <div class="flex flex-col min-w-full max-w-md bg-[var(--f-background-variant-1)] border border-[var(--f-text-variant-2)] rounded-lg shadow h-auto">
        <a href="{{ url('/vehicle', ['vehicle' => $vehicle]) }}">
            <img class="object-fill w-full h-full aspect-video rounded-t-lg transform" src="{{ $vehicle->photoUrl }}" alt=""/>
        </a>

        <div class="px-5 py-1 flex flex-col flex-grow">
            <a href="{{ url('/vehicle', ['vehicle' => $vehicle]) }}" class="mb-4">
                <h4 class="flex flex-col mt-1 text-md font-bold tracking-tight text-[var(--f-text-variant-5)] ">
                    <span class="">{{ strtoupper($vehicle->model->brand->name) }} {{strtoupper($vehicle->model->name)}}</span>
                </h4>

                <p class="mb-2">
                    <span class="text-[var(--f-text-variant-6)] border-r border-[var(--f-text-variant-3)] pr-2 tracking-wide">{{ $vehicle->year_one }}/{{ $vehicle->year_two }}</span>
                    <span class="text-[var(--f-text-variant-6)] pl-2">{{ number_format($vehicle->km, 0, '', '.')  }} KM</span>
                </p>

                @if($vehicle->promotional_price)
                <div class="flex gap-2">
                    <p class="text-[var(--f-text-variant-6)] line-through text-sm">
                        R$ {{ number_format($vehicle->sale_price, 2, ',', '.') }}
                    </p>

                    <p class="text-lg mb-auto font-bold text-[var(--f-secondary-color)]">
                        R$ {{ number_format($vehicle->promotional_price, 2, ',', '.') }}
                    </p>
                </div>
                @else
                    <p class="text-lg mb-auto font-bold text-[var(--f-text-variant-6)]">
                        R$ {{ number_format($vehicle->sale_price, 2, ',', '.') }}
                    </p>
                @endif
            </a>

            <a href="{{ $vehicle->store->gerarLinkGoogleMaps() }}"
                class="text-[var(--f-text-variant-6)] cursor-pointer p-2 rounded-lg flex items-center justify-center gap-2 hover:text-blue-700 hover:underline"
                target="_blank">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path strokeLinecap="round" strokeLinejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <span > {{ $vehicle->store->city }} - {{ $vehicle->store->state }} </span>
            </a>
        </div>
    </div>
</div>
