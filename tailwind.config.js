/** @type {import('tailwindcss').Config} */
export default {
  content: [
        './app/Filament/Admin/**/*.php',
        './app/Filament/Master/**/*.php',
        './app/Livewire/**/*.php',
        './resources/views/**/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

