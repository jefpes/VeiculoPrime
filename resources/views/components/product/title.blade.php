@props(['product'])

<h1 class="text-[var(--f-text-variant-8)] font-bold text-2xl mt-2">
    {{ strtoupper($product->model->name) }}
</h1>
