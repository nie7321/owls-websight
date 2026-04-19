<x-guest-layout>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                Portal (2002)
            </h1>
        </div>

        <article>
            <div class="prose max-w-none pb-8 pt-10 dark:prose-invert">
                <p>Portal was a show on G4 back in the early '00s that explored the MMOs of the era. I've <a href="/tags/portal">posted about it</a> a couple times. I've never seen a thorough wikipedia-style breakdown of the episodes, so this is my effort to document the series.</p>
                <p>Each episode has a detailed description, a list of the characters who appeared in it, and a link to watch it.</p>
            </div>

            @php /** @var \Illuminate\Support\Collection<\App\Domains\Portal\Models\PortalSeason> $episodesBySeason */ @endphp
            @foreach ($episodesBySeason as $season)
                <h2 class="text-3xl font-extrabold leading-6 tracking-tight text-gray-900 dark:text-gray-100 md:leading-9 mb-6">{{ $season->name }}</h2>

                <table class="w-full text-sm text-left rtl:text-right mb-12">
                    <thead class="text-xs text-gray-800 uppercase bg-gray-200 dark:bg-gray-900 dark:text-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">#</th>
                        <th scope="col" class="px-6 py-3 font-medium">Name</th>
                        <th scope="col" class="px-6 py-3 font-medium">Aired</th>
                        <th scope="col" class="px-6 py-3 font-medium">Summary</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($season->episodes as $episode)
                        @php
                            /** @var \App\Domains\Portal\Models\PortalEpisode $episode */
                            $link = route('portal.episode', [$season->season_number, $episode->episode_number]);
                        @endphp

                        <tr class="border-b dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-4 tabular-nums">
                                <a href="{{ $link }}" class="flex w-100 justify-start">
                                    {{ $episode->episode_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $link }}" class="flex w-100 justify-start underline underline-offset-2">
                                    {{ $episode->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $link }}" class="flex w-100 justify-start">
                                    {{ $episode->air_date->format('Y-m-d') }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ $link }}" class="flex w-100 justify-start text-wrap">
                                    {!! $toMarkdown->convert($episode->short_description) !!}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <td colspan="4">
                                No episodes!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            @endforeach
        </article>
    </div>
</x-guest-layout>
