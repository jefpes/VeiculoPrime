@props(['label' => null,'messages' => null])
<div>
  @if ($label)
    <label class="block text-sm font-medium"> {{ __($label) }} </label>
  @endif
  <select
    {{ $attributes->merge(['class' => 'p-1 bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block']) }}>
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
