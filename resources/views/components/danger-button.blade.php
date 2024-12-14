@php
$company = App\Models\Company::query()->first();
$btnColor = $company->btn_3_color ?? '#000';
$btnTextColor = $company->btn_3_text_color ?? '#fff';
@endphp

<button style="background-color: {{ $btnColor }}; color: {{ $btnTextColor }};"
{{ $attributes->merge(['class' => 'flex-1 focus:outline-none focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2']) }}>
    {{ $slot }}
</button>
