<div class="flex flex-col gap-2">

    <x-vehicles.filters.select id="select-order" label="Order" wire:model.live="order">
        <option value="asc"> {{ __('Growing') }}</option>
        <option value="desc">{{ __('Descending') }}</option>
    </x-vehicles.filters.select>

    <x-vehicles.filters.select id="select-type" label="Type" wire:model.live="vehicle_type">
        <option value="" selected> {{ __('Select one') }}</option>
        @foreach ($this->types as $data)
            <option value="{{ $data->name }}">{{ $data->name }}</option>
        @endforeach
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
