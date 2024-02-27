<x-guest-layout>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                {{ $category->label }}
            </h1>
            <p>If you want to be listed on the blogroll, <a href="{{ route('contact') }}" class="underline">let me know!</a></p>
        </div>

        <div class="container py-12">
        @if ($links->count() > 0)
            <div class="-m-4 flex flex-wrap">
            @php /** @var \App\Domains\Blog\Models\Link $link */ @endphp
            @foreach($links as $link)
                @include('link._card')
            @endforeach
            </div>
        @else
            <div class="text-3xl font-extrabold leading-9 text-center">No links!</div>
        @endif
        </div>
    </div>
    <div class="text-base font-medium leading-6">
        {{ $links->links('blog._pager') }}
    </div>
</x-guest-layout>
