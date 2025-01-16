<footer class="container mx-auto px-4 py-8">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="space-y-4">
        <h3 class="text-lg font-semibold">{{ __('About') }}</h3>
        <p>{{ $company->about ?? 'Motor Market is a platform for buying and selling cars.' }}</p>
        @if($company->addresses->isNotEmpty())
            @if ($company->addresses->count() > 1)
                <h3 class="text-lg font-semibold">{{ __('Addresses') }}</h3>
                @else
                <h3 class="text-lg font-semibold">{{ __('Address') }}</h3>
            @endif
            @foreach ($company->addresses as $address)
                <p>{{ $address->getFullAddressAttribute() }}</p>
            @endforeach
        @endif
        @if($company->cnpj)
            <p><span class="font-semibold">{{ __('CNPJ') }}:</span> {{ $company->cnpj }}</p>
        @endif
    </div>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold">{{ __('Contacts') }}</h3>
        <div class="flex space-x-4">
        @if($company->x)
        <a href="{{ 'https://'.$company->x }}" target="_blank"
            class="transition-colors duration-200">
            <x-icons.x class="w-6 h-6" />
        </a>
        @endif
        @if($company->facebook)
        <a href="{{ 'https://'.$company->facebook }}" target="_blank"
            class="transition-colors duration-200">
            <x-icons.facebook class="w-6 h-6" />
        </a>
        @endif
        @if($company->instagram)
        <a href="{{ 'https://'.$company->instagram }}" target="_blank"
            class="transition-colors duration-200">
            <x-icons.instagram class="w-6 h-6" />
        </a>
        @endif
        @if($company->linkedin)
        <a href="{{ 'https://'.$company->linkedin }}" target="_blank"
            class="transition-colors duration-200">
            <x-icons.linkedin class="w-6 h-6" />
        </a>
        @endif
        @if($company->youtube)
        <a href="{{ 'https://'.$company->youtube }}" target="_blank"
            class=" transition-colors duration-200">
            <x-icons.youtube class="w-6 h-6" />
        </a>
        @endif
        @if($company->whatsapp)
        <a href="{{ 'https://'.$company->whatsapp }}" target="_blank"
            class=" transition-colors duration-200">
            <x-icons.whatsapp class="w-6 h-6" />
        </a>
        @endif
        </div>
    @if($company->phones->isNotEmpty())
        @if ($company->phones->count() > 1)
        <h3 class="text-lg font-semibold">{{ __('Phones') }}</h3>
        @else
        <h3 class="text-lg font-semibold">{{ __('Phone') }}</h3>
        @endif
        @foreach ($company->phones as $phone)
        <p>{{ $phone->full_phone }}
            @if ($phone->type)
                ({{ $phone->type }})
            @endif
        </p>
        @endforeach
    @endif
      @if($company->email)
      <p><span class="font-semibold">{{ __('E-mail') }}:</span> {{ $company->email }}</p>
      @endif
    </div>
  </div>
  <div class="mt-8 pt-8 border-t border-gray-200 text-center">
    &copy; {{ date('Y') }} {{ $company->name ?? 'Motor Market' }}. {{ __('All rights reserved.') }}
  </div>
</footer>
