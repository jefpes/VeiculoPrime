@props(['label' => null,'messages' => null])
<div>
  @if ($label)
    <label class="block text-sm font-medium"> {{ __($label) }} </label>
  @endif
  <select style="{{ 'background-color:' . App\Models\Company::query()->first()->btn_1_color . ';color:' . App\Models\Company::query()->first()->btn_1_text_color }}"
    {{ $attributes->merge(['class' => 'p-1 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block']) }}>
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
