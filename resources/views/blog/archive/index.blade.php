@php
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Domains\Blog\DTOs\ArchiveYear;

/** @var Collection<ArchiveYear> $summary */
@endphp
<x-guest-layout title="Archives">
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                Archive
            </h1>
            <p class="text-lg leading-7 text-gray-500 dark:text-gray-400">Wow, look at all those posts!</p>
        </div>

        <div class="pt-4 flex flex-wrap">
            @foreach ($summary as $yearSummary)
                <div class="w-full sm:w-1/3 pb-8">
                    <h2 class="text-2xl font-bold leading-8 tracking-tight">
                        {{ $yearSummary->year }}
                        ({{ Str::plural('post', $yearSummary->totalPosts(), prependCount: true) }})
                    </h2>

                    <ul class="ps-4">
                        @foreach($yearSummary->months as $monthSummary)
                            <li class="text-lg">
                                <a class="underline" href="{{ route('blog-archive.show', [$monthSummary->year, $monthSummary->padMonth()]) }}">{{ $monthSummary->monthLabel() }}</a>
                                ({{ Str::plural('post', $monthSummary->postCount, prependCount: true) }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-guest-layout>
