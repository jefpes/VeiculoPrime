@props(['product'])

<div class="mt-4">
    <h3 class="text-[var(--f-text-variant-8)] font-semibold text-lg">
        {{ trans('Accessories and more') }}
    </h3>

    <div class="mt-4 grid grid-cols-2 gap-4">
        @foreach(array_chunk(range(1, 18), 9) as $chunk)
            <div>
                @foreach($chunk as $index)
                    <div class="flex gap-2 my-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                  d="M14.6 8.075q0-1.075-.712-1.725T12 5.7q-.725 0-1.312.313t-1.013.912q-.4.575-1.088.663T7.4 7.225q-.35-.325-.387-.8t.237-.9q.8-1.2 2.038-1.862T12 3q2.425 0 3.938 1.375t1.512 3.6q0 1.125-.475 2.025t-1.75 2.125q-.925.875-1.25 1.363T13.55 14.6q-.1.6-.513 1t-.987.4t-.987-.387t-.413-.963q0-.975.425-1.787T12.5 11.15q1.275-1.125 1.688-1.737t.412-1.338M12 22q-.825 0-1.412-.587T10 20t.588-1.412T12 18t1.413.588T14 20t-.587 1.413T12 22"/>
                        </svg>
                        <span class="text-[var(--f-text-variant-6)]">
                            Any Statistic
                        </span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
