/** @type {import('tailwindcss').Config} */
export default {
  content: [
        './app/Filament/**/*.php',
        './app/Livewire/**/*.php',
        './resources/views/**/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

