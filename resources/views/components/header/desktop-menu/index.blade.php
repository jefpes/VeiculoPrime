<ul class="hidden items-center gap-4 sm:flex">
    {{-- <x-header.desktop-menu.dropdown text="Dropdown Menu">
        <x-header.desktop-menu.dropdown.link href="#" text="Dropdown Link 1" />
        <x-header.desktop-menu.dropdown.link href="#" text="Dropdown Link 2" />
        <x-header.desktop-menu.dropdown.link href="#" text="Dropdown Link 3" />
    </x-header.desktop-menu.dropdown> --}}

    <!-- CTA Button -->
    @if (tenant())
        <x-header.desktop-menu.button text="Login" href="{{ route('filament.admin.auth.login') }}" />
    @endif
</ul>
