<div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
     @keydown.esc.window="modalIsOpen = false" @click.self="modalIsOpen = false"
     class="fixed inset-0 z-30 flex items-end justify-center bg-[var(--f-primary-color)]/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8"
     role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
    <div x-show="modalIsOpen"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
         class="flex max-w-lg flex-col gap-4 overflow-hidden rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-background-variant-1)] text-[var(--f-neutral-600)]">
        <div
            class="flex items-center justify-between border-b border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)]/60 p-4">
            <h3 id="defaultModalTitle" class="font-semibold tracking-wide text-[var(--f-neutral-900)]">
                {{ trans('Payment methods') }}
            </h3>

            <button @click="modalIsOpen = false" aria-label="close modal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-4 py-2">
            <p>
                {{ trans('You choose the shape that suits you best!') }}
            </p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                @foreach($methods as $method)
                    @php
                        $method = (object) $method;
                    @endphp
                    <x-product.payment-methods.card :method="$method"/>
                @endforeach
            </div>
        </div>
    </div>
</div>
