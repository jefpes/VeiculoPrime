@props(['settings'])
<div class="grid grid-cols-2 gap-2 mt-4 sm:justify-center sm:mt-0">
    @if($settings->whatsapp)
        <x-footer.social-links.link href="https://api.whatsapp.com/send?phone={{ $settings->whatsapp }}" text="Whatsapp">
            <x-icons.whatsapp class="w-4 h-4"/>
        </x-footer.social-links.link>
    @endif

    @if($settings->x)
        <x-footer.social-links.link href="{{ url($settings->x) }}" text="X">
            <x-icons.x class="w-4 h-4"/>
        </x-footer.social-links.link>
    @endif

    @if($settings->instagram)
        <x-footer.social-links.link href="{{ $settings->instagram }}" text="Instagram">
            <x-icons.instagram class="w-4 h-4"/>
        </x-footer.social-links.link>
    @endif

    @if($settings->facebook)
        <x-footer.social-links.link href="{{ $settings->facebook }}" text="Facebook">
            <x-icons.facebook class="w-4 h-4"/>
        </x-footer.social-links.link>
    @endif

    @if($settings->linkedin)
        <x-footer.social-links.link href="{{ $settings->linkedin }}" text="Linkedin">
            <x-icons.linkedin class="w-4 h-4"/>
        </x-footer.social-links.link>
    @endif

    @if($settings->youtube)
        <x-footer.social-links.link href="{{ $settings->youtube }}" text="Youtube">
            <x-icons.youtube class="w-4 h-4"/>
        </x-footer.social-links.link>
    @endif
</div>
