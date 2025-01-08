<div>
    <x-vehicle.banners :vehicle="$vehicle"/>

    <section class="container mt-6 mx-auto px-4">
        <x-vehicle.header :vehicle="$vehicle"/>
        <x-vehicle.title :vehicle="$vehicle"/>

        {{--        <x-vehicle.header-details :vehicle="$vehicle"/>--}}

        <x-vehicle.location :vehicle="$vehicle"/>

        <x-vehicle.price :vehicle="$vehicle" :paymentMethods="$paymentMethods"/>

        <x-vehicle.characteristics :vehicle="$vehicle"/>

        <x-vehicle.accessories :vehicle="$vehicle"/>

        <x-vehicle.extras :vehicle="$vehicle"/>

        @if($vehicle->description)
        <div class="mt-2">
            <h3 class="text-[var(--f-text-variant-8)] font-semibold text-lg">
                {{ trans('Description') }}
            </h3>

            <p class="text-[var(--f-text-variant-6)] font-normal text-base mt-1">
                {{ $vehicle->description }}
            </p>
        </div>
        @endif

        <x-vehicle.similar-vehicles :vehicles="$similarVehicles"/>
    </section>
</div>
