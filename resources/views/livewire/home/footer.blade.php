<footer class="container mx-auto px-4 py-8">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="space-y-4">
      <h3 class="text-lg font-semibold">{{ __('About') }}</h3>
      <p class="text-gray-600">{{ $company->about ?? 'Motor Market is a platform for buying and selling cars.' }}</p>
      @if($company->address)
      <p class="text-gray-600"><span class="font-semibold">{{ __('Address') }}:</span> {{ $company->address }}</p>
      @endif
      @if($company->cnpj)
      <p class="text-gray-600"><span class="font-semibold">{{ __('CNPJ') }}:</span> {{ $company->cnpj }}</p>
      @endif
    </div>
    <div class="space-y-4">
      <h3 class="text-lg font-semibold">{{ __('Contacts') }}</h3>
      <div class="flex space-x-4">
        @if($company->x)
        <a href="{{ 'https://'.$company->x }}" target="_blank"
          class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
          <x-icons.x class="w-6 h-6" />
        </a>
        @endif
        @if($company->facebook)
        <a href="{{ 'https://'.$company->facebook }}" target="_blank"
          class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
          <x-icons.facebook class="w-6 h-6" />
        </a>
        @endif
        @if($company->instagram)
        <a href="{{ 'https://'.$company->instagram }}" target="_blank"
          class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
          <x-icons.instagram class="w-6 h-6" />
        </a>
        @endif
        @if($company->linkedin)
        <a href="{{ 'https://'.$company->linkedin }}" target="_blank"
          class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
          <x-icons.linkedin class="w-6 h-6" />
        </a>
        @endif
        @if($company->youtube)
        <a href="{{ 'https://'.$company->youtube }}" target="_blank"
          class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
          <x-icons.youtube class="w-6 h-6" />
        </a>
        @endif
        @if($company->whatsapp)
        <a href="{{ 'https://'.$company->whatsapp }}" target="_blank"
          class="text-gray-600 hover:text-blue-600 transition-colors duration-200">
          <x-icons.whatsapp class="w-6 h-6" />
        </a>
        @endif
      </div>
      @if($company->phone)
      <p class="text-gray-600"><span class="font-semibold">{{ __('Phone') }}:</span> {{ $company->phone }}</p>
      @endif
      @if($company->email)
      <p class="text-gray-600"><span class="font-semibold">{{ __('E-mail') }}:</span> {{ $company->email }}</p>
      @endif
    </div>
  </div>
  <div class="mt-8 pt-8 border-t border-gray-200 text-center text-gray-600">
    &copy; {{ date('Y') }} {{ $company->name ?? 'Motor Market' }}. {{ __('All rights reserved.') }}
  </div>
</footer>
