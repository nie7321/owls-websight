<x-guest-layout>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                {{ $character->name }}
            </h1>
        </div>

        <article>
            <div class="prose max-w-none pb-6 pt-10 dark:prose-invert">
                {!! $toPostMarkdown->convert($character->description) !!}
            </div>


            <h2 class="text-3xl font-extrabold leading-6 tracking-tight text-gray-900 dark:text-gray-100 md:leading-9 mb-6">
                Appearances
            </h2>

            <table class="w-full text-sm text-left rtl:text-right mb-12">
                <thead class="text-xs text-gray-800 uppercase bg-gray-200 dark:bg-gray-900 dark:text-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">#</th>
                    <th scope="col" class="px-6 py-3 font-medium">Name</th>
                    <th scope="col" class="px-6 py-3 font-medium">Role in the Episode</th>
                </tr>
                </thead>
                <tbody>

                @forelse($character->episodes as $episode)
                    @php
                        /** @var \App\Domains\Portal\Models\PortalEpisode $episode */
                        $link = route('portal.episode', [$episode->season->season_number, $episode->episode_number]);
                    @endphp

                    <tr class="border-b dark:border-gray-700 border-gray-200">
                        <td class="px-6 py-4 tabular-nums">
                            <a href="{{ $link }}" class="flex w-100 justify-start">
                                S{{ $episode->season->season_number }} E{{ $episode->episode_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ $link }}" class="flex w-100 justify-start underline underline-offset-2">
                                {{ $episode->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ $link }}" class="flex w-100 justify-start text-wrap">
                                {!! $toSummaryMarkdown->convert($episode->pivot->role_in_episode) !!}
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
        </article>
    </div>
</x-guest-layout>
