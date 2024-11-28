@props(['label' => null,'messages' => null])
<div>
  @if ($label)
    <label class="block text-sm font-medium text-gray-700 dark:text-white"> {{ __($label) }} </label>
  @endif
  <select
    {{ $attributes->merge(['class' => 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }}>
    {{ $slot }}
  </select>

  @if ($messages)
  <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
    @foreach ((array) $messages as $message)
    <li>{{ $message }}</li>
    @endforeach
  </ul>
  @endif
</div>
