<div class="flex flex-col gap-2">
    <x-vehicles.filters.text-input
        name="Text Input"
        placeholder="Search for..."
        id="text-input"
    />

    <div class="flex gap-2 mt-2">
        <x-vehicles.filters.select
            name="Select 1"
            id="select"
            label="Select 1"
            optionSelected="Select one"
            :options="['Option 1', 'Option 2', 'Option 3']" />

        <x-vehicles.filters.select
            name="Select 2"
            id="select"
            label="Select 2"
            optionSelected="Select one"
            :options="['Option 1', 'Option 2', 'Option 3']" />
    </div>

    <x-vehicles.filters.select
        name="Select 3"
        id="select-3"
        label="Select 3"
        optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

    <x-vehicles.filters.select
        name="Select 4"
        id="select-4"
        label="Select 4"
        optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

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

    <div class="mt-2">
        <x-vehicles.filters.clean-filters />
    </div>
</div>
