@props([
    'text' => '',
])

<button type="button"
        @mouseover="isOpen = true"
        @keydown.space.prevent="openedWithKeyboard = true"
        @keydown.enter.prevent="openedWithKeyboard = true"
        @keydown.down.prevent="openedWithKeyboard = true"
        class="flex items-center gap -1text-black underline-offset-2 hover:text-black focus:outline-none focus:underline transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[var(--f-neutral-800)]" :class="isOpen || openedWithKeyboard ? 'text-[var(--f-neutral-900)]' : 'text-[var(--f-neutral-600)]'" :aria-expanded="isOpen || openedWithKeyboard" aria-haspopup="true">
    {{ trans($text) }}
</button>
