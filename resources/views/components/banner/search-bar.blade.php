<div class="relative flex !w-[32rem] flex-col gap-1 text-[var(--f-neutral-600)] -mt-1 z-20">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
         stroke="currentColor" aria-hidden="true"
         class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-[var(--f-neutral-600)]/50">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
    </svg>
    <input type="search"
           class="w-full rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] py-4 pl-10 pr-2 text-sm disabled:cursor-not-allowed disabled:opacity-75"
           name="search" placeholder="{{ trans('Search to Brand or Model') }}" aria-label="search"/>
    <button type="button"
            class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md bg-[var(--f-primary-color)] px-4 py-2 text-center text-sm font-medium tracking-wide text-[var(--f-neutral-100)] hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
        {{ trans('Search') }}
    </button>
</div>
