@props(['vehicle'])
<div class="mt-2">
    <h3 class="text-[var(--f-text-variant-8)] font-semibold text-lg">
        {{ trans('Caracteristics') }}
    </h3>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-4 mb-4">
        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32"
                 height="32" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                <path d="M13.45 11.55l2.05 -2.05"></path>
                <path d="M6.4 20a9 9 0 1 1 11.2 0z"></path>
            </svg>

            <span class="text-[var(--f-text-variant-8)]">
                        {{ $vehicle->km }} km
            </span>
        </div>

        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32"
                 height="32" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                <path d="M16 3l0 4"></path>
                <path d="M8 3l0 4"></path>
                <path d="M4 11l16 0"></path>
                <path d="M8 15h2v2h-2z"></path>
            </svg>

            <span class="text-[var(--f-text-variant-8)] mt-2">
                        {{ $vehicle->year_one }} / {{ $vehicle->year_two }}
            </span>
        </div>

        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                 viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-width="1.5">
                    <path
                        d="M16 22V8c0-2.828 0-4.243-.879-5.121C14.243 2 12.828 2 10 2H9c-2.828 0-4.243 0-5.121.879C3 3.757 3 5.172 3 8v14"/>
                    <path
                        d="M11 6H8c-.943 0-1.414 0-1.707.293S6 7.057 6 8s0 1.414.293 1.707S7.057 10 8 10h3c.943 0 1.414 0 1.707-.293S13 8.943 13 8s0-1.414-.293-1.707S11.943 6 11 6Z"/>
                    <path stroke-linecap="round"
                          d="M7 17h5m5 5H2M19.5 4l1.233.986c.138.11.207.166.27.222a3 3 0 0 1 .992 2.066c.005.084.005.172.005.348V18.5a1.5 1.5 0 0 1-3 0v-.071c0-.79-.64-1.429-1.429-1.429H16"/>
                    <path stroke-linecap="round"
                          d="M22 8h-1.5A1.5 1.5 0 0 0 19 9.5v2.419a1.5 1.5 0 0 0 1.026 1.423L22 14"/>
                </g>
            </svg>

            <span class="text-[var(--f-text-variant-8)] mt-2">
                        {{ ucfirst(Str::lower($vehicle->fuel)) }}
            </span>
        </div>

        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                 viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 4v16m7-16v16m7-16v8H5"/>
            </svg>

            <span class="text-[var(--f-text-variant-8)] mt-2">
                {{ ucfirst(Str::lower($vehicle->transmission)) }}
            </span>
        </div>

        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                 viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 10v6"></path>
                <path d="M12 5v3"></path>
                <path d="M10 5h4"></path>
                <path d="M5 13h-2"></path>
                <path
                    d="M6 10h2l2 -2h3.382a1 1 0 0 1 .894 .553l1.448 2.894a1 1 0 0 0 .894 .553h1.382v-2h2a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-2v-2h-3v2a1 1 0 0 1 -1 1h-3.465a1 1 0 0 1 -.832 -.445l-1.703 -2.555h-2v-6z"></path>
            </svg>

            <span class="text-[var(--f-text-variant-8)]">
                {{ $vehicle->engine_power }}
            </span>
        </div>

        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                 viewBox="0 0 24 24">
                <path fill="currentColor" d="M12.75 3a.75.75 0 0 0-1.5 0v2a.75.75 0 0 0 1.5 0z"/>
                <path fill="currentColor" fill-rule="evenodd"
                      d="M22.75 12.057c0 1.837 0 3.293-.153 4.432c-.158 1.172-.49 2.121-1.238 2.87c-.749.748-1.698 1.08-2.87 1.238c-1.14.153-2.595.153-4.433.153H9.944c-1.837 0-3.293 0-4.432-.153c-1.172-.158-2.121-.49-2.87-1.238c-.748-.749-1.08-1.698-1.238-2.87c-.153-1.14-.153-2.595-.153-4.433v-.926q.001-.575.008-1.096c.014-.975.05-1.81.145-2.523c.158-1.172.49-2.121 1.238-2.87c.749-.748 1.698-1.08 2.87-1.238c.716-.096 1.558-.132 2.541-.145l.697-.005a1 1 0 0 1 1.001.999V5a2.25 2.25 0 0 0 4.5 0v-.75c0-.552.448-1 1-.998c1.29.006 2.359.033 3.239.151c1.172.158 2.121.49 2.87 1.238c.748.749 1.08 1.698 1.238 2.87c.153 1.14.153 2.595.153 4.433zM8 9.75a.75.75 0 0 0 0 1.5h8a.75.75 0 0 0 0-1.5zm0 3.5a.75.75 0 0 0 0 1.5h5.5a.75.75 0 0 0 0-1.5z"
                      clip-rule="evenodd"/>
            </svg>

            <span class="text-[var(--f-text-variant-8)] mt-2">
                 {{ trans('End of plate:') }} {{ $vehicle->plate[-1] }}
            </span>
        </div>

        <div
            class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
            <svg class="text-[var(--f-text-variant-8)]" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                 viewBox="0 0 24 24">
                <path fill="currentColor" fill-rule="evenodd"
                      d="M10.847 21.934C5.867 21.362 2 17.133 2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.157-3.283 4.733-6.086 4.37c-1.618-.209-3.075-.397-3.652.518c-.395.626.032 1.406.555 1.929a1.673 1.673 0 0 1 0 2.366c-.523.523-1.235.836-1.97.751M11.085 7a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0M6.5 13a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m11 0a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3m-3-4.5a1.5 1.5 0 1 0 0-3a1.5 1.5 0 0 0 0 3"
                      clip-rule="evenodd"/>
            </svg>

            <span class="text-[var(--f-text-variant-8)] mt-2">
                {{ ucfirst(Str::lower($vehicle->color)) }}
            </span>
        </div>

        @if($vehicle->doors)
            <div
                class="bg-[var(--f-text-variant-2)] rounded-lg flex flex-col justify-center items-center px-4 py-2">
                <svg width="32" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20.7334 20.3333H24.7937M27.5 24.3333V7.66667C27.5 6.19391 26.2884 5 24.7937 5H16.4942C15.7462 5 15.0315 5.30509 14.5199 5.84282L6.232 14.5538C5.57601 15.2433 5.33939 16.2234 5.61002 17.1301L7.98468 25.0858C8.32343 26.2207 9.38047 27 10.581 27H24.7937C26.2884 27 27.5 25.8061 27.5 24.3333ZM24.7937 9.66667V15C24.7937 15.7364 24.1879 16.3333 23.4406 16.3333H12.9852C11.8078 16.3333 11.1921 14.9542 11.9877 14.099L16.9492 8.7657C17.2055 8.4902 17.5674 8.33333 17.9467 8.33333H23.4406C24.1879 8.33333 24.7937 8.93029 24.7937 9.66667Z"
                        stroke="#232323" stroke-width="2" stroke-linecap="round"></path>
                </svg>

                <span class="text-[var(--f-text-variant-8)] mt-2">
                        {{ $vehicle->doors }} {{ trans('Doors') }}
                </span>
                @endif
            </div>
    </div>
