@props(['vehicle'])
<p class="text-[var(--f-text-variant-6)] mt-2 flex">
    <span class="border-r border-[var(--f-text-variant-4)] pr-2 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none"><path
                    stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                    d="M14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14v-2c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12v2c0 3.771 0 5.657-1.172 6.828c-.653.654-1.528.943-2.828 1.07M7 4V2.5M17 4V2.5M21.5 9H10.75M2 9h3.875"/><path
                    fill="currentColor"
                    d="M18 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-5 4a1 1 0 1 1-2 0a1 1 0 0 1 2 0m0-4a1 1 0 1 1-2 0a1 1 0 0 1 2 0"/></g></svg>
        {{ $vehicle->year_one }} / {{ $vehicle->year_two }}aaas
    </span>
    <span class="pl-2 flex items-center">
        <div class="w-8 h-8 mt-1">
            <svg width="800px" height="800px" viewBox="0 0 76 76" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
                <path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 38,41C 40.2091,41 42,42.7909 42,45C 42,47.2091 40.2091,49 38,49C 37.2466,49 36.5418,48.7917 35.9401,48.4295L 30,45L 35.9402,41.5704C 36.5419,41.2083 37.2466,41 38,41 Z M 38,23C 49.0457,23 58,31.9543 58,43L 58,46L 48,46L 48,43C 48,37.4772 43.5228,33 38,33C 32.4771,33 28,37.4772 28,43L 28,46L 18,46L 18,43C 18,31.9543 26.9543,23 38,23 Z M 55,43C 55,38.6711 53.3819,34.7201 50.7177,31.7188L 47.8827,34.5538C 49.8262,36.8258 51,39.7758 51,43L 55,43 Z M 26.7159,30.2849L 29.5508,33.1198C 31.5805,31.3825 34.1519,30.2597 36.9766,30.0397L 36.9766,26.0303C 33.0485,26.2636 29.4794,27.8306 26.7159,30.2849 Z M 46.4702,33.1379L 49.3048,30.3033C 46.5275,27.8287 42.933,26.2517 38.9766,26.0276L 38.9766,30.0362C 41.8295,30.2481 44.4261,31.3807 46.4702,33.1379 Z M 21,43L 25,43C 25,39.7658 26.181,36.8075 28.1353,34.5328L 25.3007,31.6981C 22.6255,34.7019 21,38.6612 21,43 Z "/>
            </svg>
        </div>
        {{ $vehicle->km }} km
    </span>
</p>
