@props(['label', 'id'])

<div class="relative flex w-full flex-col gap-1 text-[var(--f-neutral-600)]">
    <label for="{{ $id }}" class="w-fit pl-0.5 text-sm">
        {{ __($label) }}
    </label>

    <select id="{{ $id }}" name="{{ $id }}"
    {{ $attributes->merge(['class' => 'w-full appearance-none rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] px-4 py-2 text-sm
        focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black
        disabled:cursor-not-allowed disabled:opacity-75']) }}>
            {{ $slot }}
    </select>
</div>
