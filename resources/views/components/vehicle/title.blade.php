@props(['vehicle'])

<h1 class="text-[var(--f-text-variant-8)] font-bold text-2xl mt-2">
    {{ strtoupper($vehicle->model->name) }}
</h1>
