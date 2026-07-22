@php
use Illuminate\Support\Collection;
use App\Domains\Blog\DTOs\TagSummary;

/** @var Collection<TagSummary> $tags */
@endphp
<x-guest-layout title="All Tags">
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                <span class="font-mono pr-2">All Tags</span>
            </h1>
        </div>

        <div class="pt-4">
            @foreach ($tags as $tag)
                <a
                    href="{{ route('tag.show', $tag->slug) }}"
                    class="inline-block bg-gray-100 text-gray-800 text-lg font-medium me-2 px-2.5 py-0.5 my-1 rounded-sm dark:bg-gray-700 dark:text-gray-300"
                >
                    <span class="underline">{{ $tag->label }}</span> ({{ $tag->postCount }})
                </a>
            @endforeach
        </div>
    </div>

</x-guest-layout>
