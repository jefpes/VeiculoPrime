@props(['product'])

<div class="flex justify-between">
    <h4 class="text-[var(--f-text-variant-7)] font-semibold text-lg">
        {{ strtoupper($product->model->brand->name) }}
    </h4>

    <div class="flex gap-6">
        <x-product.header.save/>

        <x-product.header.share/>
    </div>
</div>
