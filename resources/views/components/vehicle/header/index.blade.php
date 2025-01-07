@props(['vehicle'])

<div class="flex justify-between">
    <h4 class="text-[var(--f-text-variant-7)] font-semibold text-lg">
        {{ strtoupper($vehicle->model->brand->name) }}
    </h4>

    {{-- <div class="flex gap-6">
        <x-vehicle.header.save/>

        <x-vehicle.header.share/>
    </div> --}}
</div>
