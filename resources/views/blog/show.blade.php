@php /** @var \App\Domains\Blog\Models\BlogPost $post */ @endphp
<x-guest-layout :title="$post->title" :previewImage="$post->thumbnail_image">
    <article>
        <div class="xl:divide-y xl:divide-gray-200 xl:dark:divide-gray-700">
            <header class="pt-6 xl:pb-6">
                <div class="space-y-1 text-center">
                    <dl class="space-y-10">
                        <div>
                            <dt class="sr-only">Published on</dt>
                            <dd class="text-base font-medium leading-6 text-gray-500 dark:text-gray-400">
                                @if($post->published_at)
                                    <time datetime="{{ $post->published_at->toIso8601String() }}">
                                        {{ $post->published_at->format('l, F j, Y') }}
                                    </time>
                                @else
                                    <span class="italic">Unpublished</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                    <div>
                        <h1 class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-5xl md:leading-14">
                            {{ $post->title }}
                        </h1>
                    </div>
                </div>
            </header>
            <div
                class="grid-rows-[auto_1fr] divide-y divide-gray-200 pb-8 dark:divide-gray-700 xl:grid xl:grid-cols-4 xl:gap-x-6 xl:divide-y-0">
                <dl class="pb-10 pt-6 xl:border-b xl:border-gray-200 xl:pt-11 xl:dark:border-gray-700">
                    <dt class="sr-only">Author</dt>
                    <dd>
                        <ul class="flex flex-wrap justify-center gap-4 sm:space-x-12 xl:block xl:space-x-0 xl:space-y-8">
                            <li class="flex items-center space-x-2"><img alt="avatar" loading="lazy" width="38"
                                                                         height="38" decoding="async" data-nimg="1"
                                                                         class="h-10 w-10 rounded-full"
                                                                         style="color: transparent;"
                                                                         src="{{ asset('image/owls-avatar.png') }}">
                                <dl class="whitespace-nowrap text-sm font-medium leading-5">
                                    <dt class="sr-only">Name</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">owls</dd>
                                    <dt class="sr-only">Mastodon</dt>
                                    <dd><a target="_blank" rel="noopener noreferrer" href="https://mastodon.yshi.org/@owls"
                                           class="text-primary-500 hover:text-primary-600 dark:hover:text-primary-400">@owls@yshi.org</a>
                                    </dd>
                                </dl>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <div class="divide-y divide-gray-200 dark:divide-gray-700 xl:col-span-3 xl:row-span-2 xl:pb-0">
                    <div class="prose max-w-none pb-8 pt-10 dark:prose-invert">
                        {!! $htmlContent !!}
                    </div>
                </div>
                <footer>
                    <div
                        class="divide-gray-200 text-sm font-medium leading-5 dark:divide-gray-700 xl:col-start-1 xl:row-start-2 xl:divide-y">
                        <div class="py-4 xl:py-8"><h2
                                class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Tags</h2>
                            <div class="flex flex-wrap">
                                @forelse ($post->tags as $tag)
                                    <a
                                        class="mr-3 text-sm font-medium uppercase text-primary-500 hover:text-primary-600 dark:hover:text-primary-400"
                                        href="{{ route('tag.show', $tag->slug) }}"
                                    >
                                        {{ $tag->label }}
                                    </a>
                                @empty
                                    <span class="italic">no tags!</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 xl:pt-8"><a
                            class="text-primary-500 hover:text-primary-600 dark:hover:text-primary-400"
                            aria-label="Back to the blog" href="{{ route('blog-post.index') }}">← Back to the blog</a></div>
                </footer>
            </div>
        </div>
    </article>
</x-guest-layout>
