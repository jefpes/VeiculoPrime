<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @php
            $settings = \App\Models\Settings::first();
        @endphp

        <link rel="icon" href="{{ asset($settings->favicon) }}" type="image/x-icon">

        <style>
            :root {
                --f-primary-color: #000000 !important;
                --f-secondary-color: #10b981 !important;
                --f-tertiary-color: #dc2626 !important;

                /* Navbar */
                --f-navbar-background-color: #ffffff !important;
                --f-navbar-border-color: #d1d5db !important;

                /* Main */
                --f-main-background-color: #f3f4f6 !important;

                /* Footer */
                --f-footer-background-color: #f3f4f6 !important;

                /* Text */
                --f-text-variant-1: #f3f4f6 !important;
                --f-text-variant-2: #e5e7eb !important;
                --f-text-variant-3: #d1d5db !important;
                --f-text-variant-4: #9ca3af !important;
                --f-text-variant-5: #6b7280 !important;
                --f-text-variant-6: #4b5563 !important;
                --f-text-variant-7: #374151 !important;
                --f-text-variant-8: #1f2937 !important;
                --f-text-variant-9: #111827 !important;
                --f-text-variant-10: #030712 !important;

                /* Background */
                --f-background-variant-1: #f3f4f6 !important;

                /* Neutral */
                --f-neutral-50: #fafafa !important;
                --f-neutral-100: #f5f5f5 !important;
                --f-neutral-200: #e5e7eb !important;
                --f-neutral-300: #d4d4d4 !important;
                --f-neutral-400: #a3a3a3 !important;
                --f-neutral-500: #737373 !important;
                --f-neutral-600: #525252 !important;
                --f-neutral-700: #404040 !important;
                --f-neutral-800: #262626 !important;
                --f-neutral-900: #171717 !important;
                --f-neutral-950: #0a0a0a !important;
            }
        </style>
    </head>
    <body>
        <x-header :logo="$settings->logo"/>

        <main class="bg-[var(--f-main-background-color)]">
            {{ $slot }}
        </main>

        <x-footer :settings="$settings"/>
    </body>
</html>
