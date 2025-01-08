<div class="flex flex-col gap-2">

    <div class="flex gap-2 mt-2">
        <x-vehicles.filters.select name="select-year-ini" id="select-year-ini" label="Year initial" optionSelected="Select one"
            :options="$this->years" />

        <x-vehicles.filters.select name="select-year-final" id="select-year-final" label="Year final" optionSelected="Select one"
            :options="['Option 1', 'Option 2', 'Option 3']" />
    </div>

    <x-vehicles.filters.select name="select-type" id="select-type" label="Select a Type" optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

    <x-vehicles.filters.select name="select-model" id="select-model" label="Select a Model" optionSelected="Select one"
        :options="['Option 1', 'Option 2', 'Option 3']" />

    <x-vehicles.filters.checkbox name="check-brands" />

    <div class="mt-2">
        <x-vehicles.filters.clean-filters />
    </div>
</div>
