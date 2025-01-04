@props([
    'logo',
   ])
<header id="app-header" class="bg-[var(--f-navbar-background-color)] border-[var(--f-navbar-border-color)] border-b">
    <nav id="app-header-navigation"
         x-data="{ mobileMenuIsOpen: false }"
         @click.away="mobileMenuIsOpen = false"
         class="container flex mx-auto justify-between px-6 py-4 shadow-sm">

        <!-- Brand Logo -->
        <x-header.brand-logo logo="{{ $logo }}" />

        <!-- Desktop Menu -->
        <x-header.desktop-menu />

        <!-- Mobile Menu Actions -->
        <x-header.mobile-menu.actions />

        <!-- Mobile Menu -->
        <x-header.mobile-menu />
    </nav>
</header>
