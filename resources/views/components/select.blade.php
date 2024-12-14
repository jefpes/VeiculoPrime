@props(['label' => null,'messages' => null])

@php
$company = App\Models\Company::query()->first();
$selectColor = $company->select_color ?? '#000';
$selectTextColor = $company->select_text_color ?? '#fff';
@endphp
<div>
  @if ($label)
    <label class="block mb-2 text-sm font-medium" style="{{ 'color:' . $selectTextColor }}"> {{ __($label) }} </label>
  @endif
  <select style="{{ 'background-color:' . $selectColor . ';color:' . $selectColor }}"
    {{ $attributes->merge(['class' => 'border text-sm rounded-lg block w-full p-2']) }}>
    {{ $slot }}
  </select>

  @if ($messages)
    <ul class="text-sm text-red-600 space-y-1">
        @foreach ((array) $messages as $message)
        <li>{{ $message }}</li>
        @endforeach
    </ul>
  @endif
</div>
