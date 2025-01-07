@props(['vehicle'])

<div class="border-y border-[var(--f-text-variant-4)] py-2 flex justify-between items-center mt-4">
    <div class="flex items-center gap-3">
        <div class="">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                <path fill="currentColor" fill-rule="evenodd"
                      d="M57.5 0C47.88 0 40 7.86 40 17.451c0 1.4.17 2.76.486 4.067l-6.32 2.935l-30.613-14.22A2.5 2.5 0 0 0 2.523 10A2.5 2.5 0 0 0 0 12.5v70.29a2.5 2.5 0 0 0 1.447 2.267l31.666 14.71a2.5 2.5 0 0 0 1.076.233a2.5 2.5 0 0 0 1.032-.232l30.613-14.221l30.613 14.22A2.5 2.5 0 0 0 100 97.5V27.21a2.5 2.5 0 0 0-1.447-2.267L74.609 13.82C72.92 5.954 65.871 0 57.5 0m0 8.178c5.18 0 9.299 4.108 9.299 9.273s-4.12 9.272-9.299 9.272c-5.18 0-9.299-4.107-9.299-9.272s4.12-9.273 9.299-9.273M5 16.418l27.275 12.67l.371 64.95L5 81.192zm69.873 3.037L95 28.805v64.777L67.322 80.725l-.258-45.018l5.977-10.17c.271-.49.484-1.011.67-1.545a17.3 17.3 0 0 0 1.162-4.537m-32.506 6.703c.26.446.531.883.828 1.303l12.17 21.035c1.704 2.227 2.837 1.804 4.254-.117l4.475-7.615l.228 39.97l-28.676 13.323l-.369-64.606z"
                      color="currentColor"/>
            </svg>
        </div>
        <div class="flex gap-1">
            <p class="text-[var(--f-text-variant-6)]">
                {{ trans('This vehicle is in:') }}
            </p>
            <p class="text-[var(--f-text-variant-8)] font-semibold">
                {{ $vehicle->store->city }} - {{ $vehicle->store->state }}
            </p>
        </div>
    </div>
    <div class="pr-4">
        <button>
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 9">
                <path fill="currentColor"
                      d="M12.5 5h-9c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h9c.28 0 .5.22.5.5s-.22.5-.5.5"/>
                <path fill="currentColor"
                      d="M10 8.5a.47.47 0 0 1-.35-.15c-.2-.2-.2-.51 0-.71l3.15-3.15l-3.15-3.15c-.2-.2-.2-.51 0-.71s.51-.2.71 0l3.5 3.5c.2.2.2.51 0 .71l-3.5 3.5c-.1.1-.23.15-.35.15Z"/>
            </svg>
        </button>
    </div>
</div>
