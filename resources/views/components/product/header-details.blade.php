@props(['product'])
<p class="text-[var(--f-text-variant-6)] mt-2">
    <span class="border-r border-[var(--f-text-variant-4)] pr-2">{{ $product->year }}</span>
    <span class="pl-2">{{ $product->km }} km</span>
</p>
