<nav class="container mx-auto p-2  flex items-center justify-between ">
  <div class="flex items-center">
    @if ($company->logo && Storage::disk('public')->exists("$company->logo"))
    <a href="{{ url('/') }}" class="flex items-center">
      <img src="{{ image_path($company->logo) }}" alt="{{ $company->name ?? 'Motor Market' }}"
        class="h-12 w-auto">
    </a>
    @else
        <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold text-gray-900">
            {{ $company->name ?? 'Motor Market' }}
        </a>
    @endif
  </div>

  <div class="flex items-center space-x-4">
    <x-primary-button >
        <a href="{{ route('filament.admin.auth.login') }}" class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            {{ __('Login') }}
        </a>
    </x-primary-button>
  </div>
</nav>
