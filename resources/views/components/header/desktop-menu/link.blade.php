@props([
    'href',
    'text',
    'bold' => false,
  ])

<li>
    <a href="{{$href}}"
       @class([
          'text-black underline-offset-2 hover:text-black focus:outline-none focus:underline',
          'font-bold' => $bold,
        ])
       aria-current="page">
        {{ trans($text) }}
    </a>
</li>
