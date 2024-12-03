<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<?php
    $company = App\Models\Company::query()->first();
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company?->name ?? 'Motor Market' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset($company?->favicon) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen" style="{{ $company->font_family . '; background-color:' . $company->body_bg_color . '; color:' . $company->font_color }}">
    <header class="shadow-md" style="{{ 'background-color:' . $company->nav_color }}">
        <livewire:home.navigation />
    </header>

    <main class="flex-grow container mx-auto md:px-4 py-4">
        {{ $slot }}
    </main>

    <footer class="shadow-md mt-auto" style="{{ 'background-color:' . $company->footer_color }}">
        <livewire:home.footer />
    </footer>

    @livewireScripts
</body>

</html>
