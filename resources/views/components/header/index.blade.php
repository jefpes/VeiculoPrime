@props([
    'settings',
   ])
<header id="app-header" class="bg-[var(--f-navbar-background-color)] border-[var(--f-navbar-border-color)] border-b">
    <nav id="app-header-navigation"
         x-data="{ mobileMenuIsOpen: false }"
         @click.away="mobileMenuIsOpen = false"
         class="container flex mx-auto justify-between px-6 py-4 shadow-sm">

        <!-- Brand Logo -->
        <x-header.brand-logo :settings="$settings" />

        <ul class="hidden items-center gap-4 sm:flex">
            <x-header.desktop-menu.link href="{{ route('index') }}" text="Home page" :bold="true" />
            <x-header.desktop-menu.link href="{{ route('vehicles') }}" text="Vehicles" :bold="true" />
            <x-header.desktop-menu.link href="{{ route('about') }}" text="About" :bold="true" />
        </ul>

        <!-- Desktop Menu -->
        <x-header.desktop-menu />

        <!-- Mobile Menu Actions -->
        <x-header.mobile-menu.actions />

        <!-- Mobile Menu -->
        <x-header.mobile-menu />
    </nav>
</header>
