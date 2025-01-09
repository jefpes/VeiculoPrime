@props(['name'])

<div class="inline-flex">
    <div class="flex items-center mb-4">
        <input id="checkbox-{{ $name }}" type="checkbox" wire:model.live="selectedBrands" :key="'brand-'.$name"
            value="{{ $name }}" class="w-4 h-4 rounded focus:ring-2 text-[var(--f-neutral-600)] border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)]">
        <label for="checkbox-{{ $name }}" class="ms-2 text-sm font-medium">{{ $name }}</label>
    </div>
</div>
