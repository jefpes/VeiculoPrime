@props(['vehicles'])

<section class="container mx-auto mt-10">
    <x-vehicles-home.title/>

    <div class="grid justify-center grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4 w-full">
        @foreach($vehicles as $vehicle)
            <x-vehicles-home.card :vehicle="$vehicle"/>
        @endforeach
    </div>

    <div class="flex justify-center mt-8">
        <a href="{{ route('vehicles') }}" class="rounded-md bg-[var(--f-primary-color)] w-full max-w-sm px-4 py-2 text-center text-sm font-medium tracking-wide text-[var(--f-neutral-100)] hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
            {{ trans('View All') }}
        </a>
    </div>
</section>
