@props(['name', 'label'])

<div class="">
    <label for="{{$name}}" class="w-fit pl-0.5 text-sm text-[var(--f-neutral-600)]">
        {{ $label }}
    </label>
    <div x-data="{ currentVal: 20 }" class="flex w-full items-center gap-4 text-[var(--f-neutral-600)]">
        <input x-model="currentVal" id="{{$name}}" type="range" class="h-2 w-full appearance-none bg-[var(--f-neutral-50)] focus:outline-black [&::-moz-range-thumb]:size-4 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:border-none [&::-moz-range-thumb]:bg-[var(--f-primary-color)] active:[&::-moz-range-thumb]:scale-110 [&::-webkit-slider-thumb]:size-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:border-none [&::-webkit-slider-thumb]:bg-[var(--f-primary-color)] active:[&::-webkit-slider-thumb]:scale-110 [&::-moz-range-thumb]:rounded-full [&::-webkit-slider-thumb]:rounded-full rounded-full" min="0" max="100" step="1" />
        <span class="w-10 text-lg font-bold text-[var(--f-neutral-900)] " x-text="currentVal"></span>
    </div>
</div>
