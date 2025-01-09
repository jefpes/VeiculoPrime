<div class="flex flex-col gap-x-2 gap-y-4">

    <x-vehicles.filters.select id="select-type" label="Type" wire:model.live="vehicle_type">
        <option value="" selected> {{ __('Select one') }}</option>
        @foreach ($this->types as $data)
        <option value="{{ $data->name }}">{{ $data->name }}</option>
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
        <option value="asc"> {{ __('Growing') }}</option>
        <option value="desc">{{ __('Descending') }}</option>
    </x-vehicles.filters.select>

    <div class="flex gap-2 mt-2">
        <x-vehicles.filters.select id="select-year-ini" label="Year initial" wire:model.live="year_ini" >
            <option value="" selected> {{ __('Select one') }}</option>
            @foreach ($this->years as $y)
                <option value="{{ $y->year_one }}">{{ $y->year_one }}</option>
            @endforeach
        </x-vehicles.filters.select>
        <x-vehicles.filters.select id="select-year-final" label="Year final" wire:model.live="year_end" >
            <option value="" selected> {{ __('Select one') }}</option>
            @foreach ($this->years as $y)
                <option value="{{ $y->year_one }}">{{ $y->year_one }}</option>
            @endforeach
        </x-vehicles.filters.select>
    </div>

    <div class="mt-2">
        <x-vehicles.filters.clean-filters wire:click="clearFilters"/>
    </div>
</div>
