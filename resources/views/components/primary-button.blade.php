@php
$company = App\Models\Company::query()->first();
$btnColor = $company->btn_1_color ?? '#000';
$btnTextColor = $company->btn_1_text_color ?? '#fff';
@endphp

<button style="background-color: {{ $btnColor }}; color: {{ $btnTextColor }};" {{ $attributes->merge(['class' => 'flex
    items-center space-x-2 px-4 py-2 rounded-md transition-colors duration-200']) }}>
    {{ $slot }}
</button>
