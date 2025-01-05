<div class="flex gap-6">
    <div class="hidden lg:block w-[30%] mt-4">
        <p class="mt-2 mb-4 text-lg font-semibold text-[var(--f-text-variant-6)]">
            {{ trans('Search for:') }}
        </p>
        <x-vehicles.filters />
    </div>

    <div class="grid justify-center grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 w-full">
        @foreach($vehicles as $vehicle)
            @php
                $vehicle = (object) $vehicle;
            @endphp
            <div class="flex justify-center">
                <x-vehicles.vehicle-card :vehicle="$vehicle" />
            </div>
        @endforeach
    </div>
</div>
