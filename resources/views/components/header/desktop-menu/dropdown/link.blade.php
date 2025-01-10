@props([
    'href',
    'text',
  ])

<a href="{{$href}}"
    @class([
       'bg-[var(--f-neutral-50)] px-4 py-2 text-sm text-[var(--f-neutral-600)] hover:bg-[var(--f-neutral-900)]/5 hover:text-[var(--f-neutral-900)] focus-visible:bg-[var(--f-neutral-900)]/10 focus-visible:text-[var(--f-neutral-900)] focus-visible:outline-none'
    ])
    role="menuitem">
    {{ trans($text) }}
</a>
