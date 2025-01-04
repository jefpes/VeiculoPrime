@props(['name', 'label', 'options', 'id', 'optionSelected'])

<div class="relative flex w-full flex-col gap-1 text-[var(--f-neutral-600)]">
    <label for="{{ $name }}"
           class="w-fit pl-0.5 text-sm">
        {{ $label }}
    </label>

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="absolute pointer-events-none right-4 top-8 h-5 w-5">
        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
    </svg>

    <select id="{{$id}}" name="{{$name}}" class="w-full appearance-none rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] px-4 py-2 text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75">
        <option selected>
            {{ $optionSelected }}
        </option>
        @foreach($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
