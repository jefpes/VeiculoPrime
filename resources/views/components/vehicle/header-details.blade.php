@props(['vehicle'])
<p class="text-[var(--f-text-variant-6)] mt-2">
    <span class="border-r border-[var(--f-text-variant-4)] pr-2">{{ $vehicle->year_one }} / {{ $vehicle->year_two }}</span>
    <span class="pl-2">{{ $vehicle->km }} km</span>
</p>
