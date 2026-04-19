<x-guest-layout>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                S{{ $episode->season->season_number }} E{{ $episode->episode_number }}: {{ $episode->name }}
            </h1>
        </div>

        <article>
            <div class="prose max-w-none pb-6 pt-10 dark:prose-invert">
                {!! $toPostMarkdown->convert($episode->full_description) !!}
            </div>

            <div class="flex flex-wrap w-full pb-6 justify-between">
                <div class="w-full md:w-[45%] mt-2 mb-2 md:mb-0 flex align-text-bottom">
                    <div>
                        <div class="text-xl">
                            {{ $episode->air_date->format('Y-m-d') }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">
                            Air Date
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-[45%] mt-2 mb-2 md:mb-0 flex align-text-bottom text-right">
                    <div class="w-full">
                        <div class="text-xl">
                            @forelse($episode->tags as $tag)
                                <a href="{{ route('tag.show', $tag->slug) }}" class="bg-gray-100 text-gray-800 text-lg font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                    {{ $tag->label }}
                                </a>
                            @empty
                                <em>n/a</em>
                            @endforelse
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">
                            Games Mentioned
                        </div>
                    </div>
                </div>

                <div class="w-full mt-4 mb-2 md:mb-0 flex align-text-bottom">
                    <div>
                        <blockquote class="p-4 border-s-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-800">
                            {!! $toSummaryMarkdown->convert($episode->g4_description) !!}
                        </blockquote>
                        <div class="text-gray-600 dark:text-gray-400">
                            G4 Summary
                        </div>
                    </div>
                </div>

                <div class="w-full mt-4 mb-2 md:mb-0 flex align-text-bottom">
                    <div>
                        <div class="text-xl">
                            @forelse($episode->characters as $character)
                                <a href="{{ route('portal.character', $character->slug) }}" class="bg-gray-100 text-gray-800 text-lg font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-gray-700 dark:text-gray-300">
                                    {{ $character->name }}
                                </a>
                            @empty
                                <em>No characters</em>
                            @endforelse
                        </div>
                        <div class="text-gray-600 dark:text-gray-400">
                            Characters
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</x-guest-layout>
