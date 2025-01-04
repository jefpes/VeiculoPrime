@props(['name', 'placeholder', 'id'])

<div class="flex w-full flex-col gap-1 text-[var(--f-neutral-600)]">
    <label for="{{$id}}" class="w-fit pl-0.5 text-sm">
        {{ $name }}
    </label>
    <input id="{{$id}}" type="text"
           class="w-full rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] px-2 py-2 text-sm disabled:cursor-not-allowed disabled:opacity-75"
           name="{{$name}}"
           placeholder="{{$placeholder}}"
           autocomplete="{{$name}}"
    />
</div>
