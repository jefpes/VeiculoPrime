@props([
    'text' => '',
])

<div x-data="{ isOpen: false, openedWithKeyboard: false, leaveTimeout: null }" @mouseleave.prevent="leaveTimeout = setTimeout(() => { isOpen = false }, 250)" @mouseenter="leaveTimeout ? clearTimeout(leaveTimeout) : true" @keydown.esc.prevent="isOpen = false, openedWithKeyboard = false" @click.outside="isOpen = false, openedWithKeyboard = false" class="relative">
    <x-header.desktop-menu.dropdown.button :text="$text" />

    <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard" @click.outside="isOpen = false, openedWithKeyboard = false" @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()" class="absolute top-11 left-0 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-[var(--f-neutral-300)] bg-[var(--f-neutral-50)] py-1.5 z-[1000000]" role="menu">
        {{ $slot }}
    </div>
</div>
