<div class="flex flex-col gap-2">

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

    {{-- <x-vehicles.filters.select name="select-type" id="select-type" label="Select a Type" optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

    <x-vehicles.filters.select name="select-model" id="select-model" label="Select a Model" optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" /> --}}

    {{-- <x-vehicles.filters.checkbox name="check-brands" /> --}}

    <div class="mt-2">
        <x-vehicles.filters.clean-filters />
    </div>
</div>
