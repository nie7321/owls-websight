<x-guest-layout :title="'Archives: '.$archiveMonth.' '.$archiveYear">
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                Archive: {{ $archiveMonth }} {{ $archiveYear }}
            </h1>
            <p class="text-lg leading-7 text-gray-500 dark:text-gray-400">Looking for something specific, huh?</p>
        </div>

        @include('blog._post-list')
    </div>
    <div class="text-base font-medium leading-6 xl:grid xl:grid-cols-4">
        <div class="xl:col-span-3 xl:col-end-5">
            {{ $posts->links('blog._pager') }}
        </div>
    </div>
</x-guest-layout>
