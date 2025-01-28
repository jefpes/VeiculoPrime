@props(['vehicle'])

<div class="hover:text-blue-700 hover:underline">
    @if ($vehicle->store->link_google_maps)
        <a href="{{ $vehicle->store->link_google_maps }}" class="border-y border-[var(--f-text-variant-4)] py-2 flex justify-between items-center mt-4">
            <div class="flex items-center gap-3">
                <div class="">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path strokeLinecap="round" strokeLinejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
                <div class="md:flex gap-1">
                    <p class="text-[var(--f-text-variant-6)]"> {{ __('Our address') }}: </p>
                    <p class="text-[var(--f-text-variant-8)] font-semibold"> {{ $vehicle->store->full_address }} </p>
                </div>
            </div>
            <div class="pr-4">
                <button>
                    <svg class="w-6 h-6" viewBox="0 0 16 9">
                        <path fill="currentColor"
                            d="M12.5 5h-9c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h9c.28 0 .5.22.5.5s-.22.5-.5.5"/>
                        <path fill="currentColor"
                            d="M10 8.5a.47.47 0 0 1-.35-.15c-.2-.2-.2-.51 0-.71l3.15-3.15l-3.15-3.15c-.2-.2-.2-.51 0-.71s.51-.2.71 0l3.5 3.5c.2.2.2.51 0 .71l-3.5 3.5c-.1.1-.23.15-.35.15Z"/>
                    </svg>
                </button>
            </div>
        </a>
        @else
        <span class="border-y border-[var(--f-text-variant-4)] py-2 flex justify-between items-center mt-4">
            <div class="flex items-center gap-3">
                <div class="">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path strokeLinecap="round" strokeLinejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
                <div class="md:flex gap-1">
                    <p class="text-[var(--f-text-variant-6)]"> {{ __('Our address') }}: </p>
                    <p class="text-[var(--f-text-variant-8)] font-semibold"> {{ $vehicle->store->full_address }} </p>
                </div>
            </div>
            <div class="pr-4">
                <button>
                    <svg class="w-6 h-6" viewBox="0 0 16 9">
                        <path fill="currentColor"
                            d="M12.5 5h-9c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h9c.28 0 .5.22.5.5s-.22.5-.5.5"/>
                        <path fill="currentColor"
                            d="M10 8.5a.47.47 0 0 1-.35-.15c-.2-.2-.2-.51 0-.71l3.15-3.15l-3.15-3.15c-.2-.2-.2-.51 0-.71s.51-.2.71 0l3.5 3.5c.2.2.2.51 0 .71l-3.5 3.5c-.1.1-.23.15-.35.15Z"/>
                    </svg>
                </button>
            </div>
        </span>
    @endif

</div>
