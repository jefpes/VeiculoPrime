@props(['vehicle'])
<p class="text-[var(--f-text-variant-6)] mt-2 flex">
    <span class="border-r border-[var(--f-text-variant-4)] pr-2 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none"><path
                    stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                    d="M14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14v-2c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828c-.653.654-1.528.943-2.828 1.07M7 4V2.5M17 4V2.5M21.5 9H10.75M2 9h3.875"/><path
                    fill="currentColor"
                    d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0"/></g></svg>
        {{ $vehicle->year_one }} / {{ $vehicle->year_two }}
    </span>
    <span class="pl-2 flex items-center">
        <img src="{{ asset('icons/kms.svg') }}" class="w-8 h-8 mt-1" alt="km icon">
        {{ $vehicle->km }} km
    </span>
</p>
