@props(['method'])
<div class="py-3 px-4 text-center bg-[#f2f2f2] rounded-lg">
    <img src="{{ $method->icon }}" alt="{{ $method->name }}" class="mx-auto rounded-lg">
    <span class="block text-[var(--f-text-variant-6)] text-sm mt-2">{{ $method->name }}</span>
</div>
