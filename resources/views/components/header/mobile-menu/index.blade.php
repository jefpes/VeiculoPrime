<ul x-cloak x-show="mobileMenuIsOpen"
    id="mobileMenu"
    x-transition:enter="transition motion-reduce:transition-none ease-out duration-300"
    x-transition:enter-start="-translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition motion-reduce:transition-none ease-out duration-300"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="-translate-y-full"
    class="fixed max-h-svh overflow-y-auto inset-x-0 top-0 z-10 flex flex-col divide-y divide-[var(--f-neutral-300)] rounded-b-md border-b border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] px-6 pb-6 pt-20  sm:hidden">

    <x-header.mobile-menu.link href="#" text="Link 1 With Bold" :bold="true" />
    <x-header.mobile-menu.link href="#" text="Second Link" />
    <x-header.mobile-menu.link href="#" text="Third Link" />

    <x-header.mobile-menu.button href="#" text="Call to Action" />
</ul>
