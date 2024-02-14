<x-guest-layout>
    <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-2 pb-8 pt-6 md:space-y-5">
            <h1
                class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
            >
                Latest Long-Form Posts
            </h1>
            <p class="text-lg leading-7 text-gray-500 dark:text-gray-400">But you should <a href="https://mastodon.yshi.org/@owls" class="underline">find me on fedi</a> for the latest short-form posts.</p>
        </div>

        @if($posts->total() === 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700 py-12">
                <p class="text-2xl font-bold leading-8 tracking-tight text-center">There are no posts!</p>
            </div>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($posts as $post)
                    <li class="py-12">
                    <article>
                        <div class="space-y-2 xl:grid xl:grid-cols-4 xl:items-baseline xl:space-y-0">
                            <dl>
                                <dt class="sr-only">Published on</dt>
                                <dd class="text-base font-medium leading-6 text-gray-500 dark:text-gray-400">
                                    @if($post->published_at)
                                        <time datetime="{{ $post->published_at->toIso8601String() }}">
                                            {{ $post->published_at->format('F j, Y') }}
                                        </time>
                                    @else
                                        <span class="italic">Unpublished</span>
                                    @endif
                                </dd>
                            </dl>
                            <div class="space-y-5 xl:col-span-3">
                                <div class="space-y-6">
                                    <div>
                                        <h2 class="text-2xl font-bold leading-8 tracking-tight">
                                            <a class="text-gray-900 dark:text-gray-100" href="{{ $post->permalink }}">
                                                {{ $post->title }}
                                            </a>
                                        </h2>
                                        <div class="flex flex-wrap">
                                            @foreach($post->tags as $tag)
                                                <a class="mr-3 text-sm font-medium uppercase text-primary-500 hover:text-primary-600 dark:hover:text-primary-400" href="#">{{ $tag->label }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="prose dark:prose-invert max-w-none text-gray-500 dark:text-gray-400">
                                        {!! $toMarkdown->convert($post->summary) !!}
                                    </div>
                                </div>
                                <div class="text-base font-medium leading-6">
                                    <a class="text-primary-500 hover:text-primary-600 dark:hover:text-primary-400"
                                       aria-label="Read more: &quot;{{ $post->title }}&quot;"
                                       href="{{ $post->permalink }}"
                                    >
                                        Read more →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="flex justify-end text-base font-medium leading-6"><a
            class="text-primary-500 hover:text-primary-600 dark:hover:text-primary-400" aria-label="All posts"
            href="/blog">All Posts →</a></div>
</x-guest-layout>
