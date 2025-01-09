<div class="flex gap-2  mt-2">
    <h4 class="text-lg font-semibold text-[var(--f-text-variant-5)]">
        {{ trans('Order') }}:
    </h4>

    <div class="flex gap-2 flex-wrap">
        <x-vehicles.order-by.button text="{{ $this->order === 'asc' ? 'Crescente' : 'Decrescente' }}" wire:click="orderBy"/>
    </div>
</div>
