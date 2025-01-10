<div class="flex justify-between"
     x-data="{
        slideOverOpen: false
    }">
    <h2 class="text-xl text-[var(--f-text-variant-6)] font-bold">
        {{ trans('Many options for you') }}
    </h2>

    <button
        type="button"
        @click="slideOverOpen = true"
        class="lg:hidden rounded-md bg-[var(--f-primary-color)] px-4 py-2 text-center text-sm font-medium tracking-wide text-[var(--f-neutral-100)] hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
        {{ trans('Filter') }}
    </button>

    <x-vehicles.drawer />
</div>
