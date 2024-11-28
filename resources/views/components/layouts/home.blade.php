<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Motor Market' }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-gray-100 text-gray-900 font-serif">
  <header class="bg-gray-200 shadow-md">
    <livewire:home.navigation />
  </header>

  <main class="flex-grow container mx-auto px-4 py-8 ">
    {{ $slot }}
  </main>

  <footer class="bg-gray-200 shadow-md mt-auto">
    <livewire:home.footer />
  </footer>

  @livewireScripts
</body>

</html>
