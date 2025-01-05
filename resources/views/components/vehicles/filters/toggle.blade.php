

<label for="{{$id}}" class="inline-flex cursor-pointer items-center gap-3">
    <input id="{{$id}}" type="checkbox" class="peer sr-only" role="switch" checked  />
    <div class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-[var(--f-neutral-600)] after:transition-all after:content-[''] peer-checked:bg-[var(--f-primary-color)] peer-checked:after:bg-[var(--f-neutral-100)] peer-focus:outline peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-[var(--f-neutral-800)] peer-focus:peer-checked:outline-black peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70" aria-hidden="true"></div>
    <span class="trancking-wide text-sm font-medium text-[var(--f-neutral-600)] peer-checked:text-[var(--f-neutral-900)] peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
        {{ $label }}
    </span>
</label>
