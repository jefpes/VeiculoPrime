<div class="flex flex-col gap-x-2 gap-y-4">

    <x-vehicles.filters.select id="select-type" label="Type" wire:model.live="vehicle_type">
        <x-vehicles.filters.select-option label="Select one" value="" selected/>
        @foreach ($this->types as $data)
        <x-vehicles.filters.select-option label="{{ $data->name }}" value="{{ $data->name }}"/>
        @endforeach
    </x-vehicles.filters.select>

    <div class="space-y-2">
        <h3 class="relative flex w-full flex-col gap-1 text-[var(--f-neutral-600)]">{{ __('Brands') }}</h3>
        <div class="grid grid-cols-2 gap-2">
            @foreach ($this->brands as $data)
                <x-vehicles.filters.checkbox name="{{ $data->name }}" value="{{ $data->name }}" />
            @endforeach
        </div>
    </div>

    <x-vehicles.filters.select id="select-order" label="Order" wire:model.live="order">
        <x-vehicles.filters.select-option label="Growing" value="asc"/>
        <x-vehicles.filters.select-option label="Descending" value="desc"/>
    </x-vehicles.filters.select>

    <div class="flex gap-2 mt-2">
        <x-vehicles.filters.select id="select-year-ini" label="Year initial" wire:model.live="year_ini" >
            <x-vehicles.filters.select-option label="Select one" value="" selected/>
            @foreach ($this->years as $data)
                <x-vehicles.filters.select-option label="{{ $data->year_one }}" value="{{ $data->year_one }}"/>
            @endforeach
        </x-vehicles.filters.select>
        <x-vehicles.filters.select id="select-year-final" label="Year final" wire:model.live="year_end" >
            <x-vehicles.filters.select-option label="Select one" value="" selected/>
            @foreach ($this->years as $data)
                <x-vehicles.filters.select-option label="{{ $data->year_one }}" value="{{ $data->year_one }}" />
            @endforeach
        </x-vehicles.filters.select>
    </div>

    <div class="mt-2">
        <x-vehicles.filters.clean-filters wire:click="clearFilters"/>
    </div>
</div>
