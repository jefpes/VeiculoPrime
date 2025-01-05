@props(['name', 'label', 'options', 'id', 'optionSelected'])

<div class="relative flex w-full flex-col gap-1 text-[var(--f-neutral-600)]">
    <label for="{{ $name }}"
           class="w-fit pl-0.5 text-sm">
        {{ $label }}
    </label>

    <select id="{{$id}}" name="{{$name}}" class="w-full appearance-none rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] px-4 py-2 text-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75">
        <option selected>
            {{ $optionSelected }}
        </option>
        @foreach($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
