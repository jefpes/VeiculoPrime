@props(['vehicle'])

<h4 class="flex flex-col mt-1 text-2xl font-bold tracking-tight text-[var(--f-text-variant-5)] ">
    <span class="">{{ strtoupper($vehicle->model->brand->name) }} {{strtoupper($vehicle->model->name)}}</span>
</h4>
