<div class="flex flex-col gap-2">
    {{-- <x-vehicles.filters.text-input
        name="Text Input"
        placeholder="Search for..."
        id="text-input"
    /> --}}

    <div class="flex gap-2 mt-2">
        <x-vehicles.filters.select name="select-year-ini" id="select-year-ini" label="Year initial" optionSelected="Select one"
            :options="['Option 1', 'Option 2', 'Option 3']" />

        <x-vehicles.filters.select name="select-year-final" id="select-year-final" label="Year final" optionSelected="Select one"
            :options="['Option 1', 'Option 2', 'Option 3']" />
    </div>

    <x-vehicles.filters.select name="select-type" id="select-type" label="Select a Type" optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

    <x-vehicles.filters.select name="select-model" id="select-model" label="Select a Model" optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

    {{--
    <div class="mt-2">
        <x-vehicles.filters.range
            name="Range"
            label="Range"
        />
    </div>

     <div class="mt-2">
        <x-vehicles.filters.toggle
            name="Toggle"
            id="toggle"
            label="Toggle"
        />
    </div>

    <div class="mt-2">
        <x-vehicles.filters.rating />
    </div>
    --}}

    <div class="mt-2">
        <x-vehicles.filters.clean-filters />
    </div>
</div>
