<div class="flex gap-2 flex-col mt-2">
    <h4 class="text-lg font-semibold text-[var(--f-text-variant-5)]">
        {{ trans('Order by') }}
    </h4>

    <div class="flex gap-2 flex-wrap">
        <x-products.order-by.button text="Order 1"/>
        <x-products.order-by.button text="Order 2"/>
        <x-products.order-by.button text="Order 3"/>
        <x-products.order-by.button text="Order 4"/>
    </div>
</div>
