<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @php
        $tenants = \App\Models\Tenant::with('setting')->get();

        $settings = \App\Models\Settings::first();

        $stores = \App\Models\Store::with('phones')->get();

        $bgStyles = [];
        $bodyStyles[] = "{$settings?->font_family};";
        $bodyStyleString = implode(' ', $bodyStyles);

        if ($settings?->bg_img) {
            $bgStyles[] = "background-image: url('" . image_path($settings?->bg_img) . "');";
            $bgStyles[] = "background-repeat: repeat;";
            $bgStyles[] = "opacity: {$settings?->bg_img_opacity};";
        }
        $bgStyleString = implode(' ', $bgStyles);
    @endphp

    <title>{{ $settings?->name ?? 'Veiculo Prime' }}</title>

    @if($settings && $settings->favicon)
        <link rel="icon" type="image/x-icon" href="{{ image_path($settings->favicon) }}">
    @endif

    <style>
        :root {
            --f-primary-color: {{  $settings->primary_color ?? '#000000'  }}  !important;
            --f-secondary-color: {{  $settings->secondary_color ?? '#10b981'  }}  !important;
            --f-tertiary-color: {{  $settings->tertiary_color ?? '#dc2626'  }}  !important;

            /* Navbar */
            --f-navbar-background-color: {{  $settings->nav_color ?? '#ffffff'  }}  !important;
            --f-navbar-border-color: {{ $settings->nav_border_color ?? '#d1d5db' }} !important;

            /* Main */
            --f-main-background-color: {{  $settings->body_bg_color ?? '#f3f4f6'  }}  !important;

            /* Footer */
            --f-footer-background-color: {{  $settings->footer_color ?? '#f3f4f6'  }}  !important;

            /* Text */
            --f-text-variant-1: {{ $settings->text_variant_color_1 ?? '#f3f4f6' }} !important;
            --f-text-variant-2: {{ $settings->text_variant_color_2 ?? '#e5e7eb' }} !important;
            --f-text-variant-3: {{ $settings->text_variant_color_3 ?? '#d1d5db' }} !important;
            --f-text-variant-4: {{ $settings->text_variant_color_4 ?? '#9ca3af' }} !important;
            --f-text-variant-5: {{ $settings->text_variant_color_5 ?? '#6b7280' }} !important;
            --f-text-variant-6: {{ $settings->text_variant_color_6 ?? '#4b5563' }} !important;
            --f-text-variant-7: {{ $settings->text_variant_color_7 ?? '#374151' }} !important;
            --f-text-variant-8: {{ $settings->text_variant_color_8 ?? '#1f2937' }} !important;
            --f-text-variant-9: {{ $settings->text_variant_color_9 ?? '#111827' }} !important;
            --f-text-variant-10: {{ $settings->text_variant_color_10 ?? '#030712' }} !important;

            /* Background */
            --f-background-variant-1: {{ $settings->card_color ?? '#f3f4f6' }}  !important;

            /* Neutral */
            --f-neutral-50:  {{ $settings->variant_color_1 ?? '#fafafa' }} !important;
            --f-neutral-100: {{ $settings->variant_color_2 ?? '#f5f5f5' }} !important;
            --f-neutral-200: {{ $settings->variant_color_3 ?? '#e5e7eb' }} !important;
            --f-neutral-300: {{ $settings->variant_color_4 ?? '#d4d4d4' }} !important;
            --f-neutral-400: {{ $settings->variant_color_5 ?? '#a3a3a3' }} !important;
            --f-neutral-500: {{ $settings->variant_color_6 ?? '#737373' }} !important;
            --f-neutral-600: {{ $settings->variant_color_7 ?? '#525252' }} !important;
            --f-neutral-700: {{ $settings->variant_color_8 ?? '#404040' }} !important;
            --f-neutral-800: {{ $settings->variant_color_9 ?? '#262626' }} !important;
            --f-neutral-900: {{ $settings->variant_color_10 ?? '#171717' }} !important;
            --f-neutral-950: {{ $settings->variant_color_11 ?? '#0a0a0a' }} !important;

            --bg-overlay-opacity: {{ $settings?->bg_img_opacity ?? '0.3' }};

            .bg-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                background-repeat: repeat;
                opacity: var(--bg-overlay-opacity);
            }
        }
    </style>
</head>
<body style="{{ $bodyStyleString }}">
    @if($settings && $settings->bg_img)
        <div class="bg-overlay" style="background-image: url('{{ image_path($settings->bg_img) }}');"></div>
    @endif

<x-header :settings="$settings"/>

<main class="bg-[var(--f-main-background-color)]">

    {{ $slot }}
</main>

<x-footer :settings="$settings" :stores="$stores" :tenants="$tenants"/>
</body>
</html>
