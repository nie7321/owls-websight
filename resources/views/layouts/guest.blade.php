<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? "{$title} | owls" : "owls" }}</title>
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }}" href="{{ route('feed.atom') }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('image/owls-avatar.png') }}">

    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('blog-post.index') }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta property="og:locale" content="en_US" />
    @isset($title)
        <meta property="og:title" content="{{ $title }}" />
    @endisset

    @if($previewImage?->exists)
        @php /** @var \App\Domains\Media\Models\Image $previewImage */@endphp
        <meta property="og:image" content="{{ $previewImage->getFirstMedia()->getUrl() }}" />
        <meta property="og:image:alt" content="{{ $previewImage->alt_description }}" />
    @else
        <meta property="og:image" content="{{ asset('image/default-social-banner.png') }}" />
        <meta property="og:image:alt" content="a cartoon owl sticking its head into an editor full of HTML code" />
    @endif

    @if ($description)
        <meta property="og:description" content="{{ $description }}" />
    @else
        <meta property="og:description" content="the personal website of owls, full of blog posts and other enjoyable web sights" />
    @endif

    <script type="text/javascript">
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-white text-black antialiased dark:bg-gray-950 dark:text-white">
<section class="mx-auto max-w-3xl px-4 sm:px-6 xl:max-w-5xl xl:px-0">
    <div class="flex h-screen flex-col justify-between font-sans">
        <header class="flex items-center justify-between py-10">
            <div><a aria-label="owlblog" href="/">
                    <div class="flex items-center justify-between">
                        <div class="mr-3">
                            <img
                                class="max-w-14 rounded-full"
                                src="{{ asset('image/owls-avatar.png') }}"
                                alt="an owl"
                                aria-hidden="true"
                            >
                        </div>
                        <div class="hidden h-6 text-2xl font-semibold sm:block">owlblog</div>
                    </div>
                </a>
            </div>
            <div class="flex items-center space-x-4 leading-5 sm:space-x-6">
                <a class="hidden font-medium text-gray-900 dark:text-gray-100 sm:block" href="{{ route('blog-post.index') }}">Home</a>
                <a class="hidden font-medium text-gray-900 dark:text-gray-100 sm:block" href="{{ route('about') }}">About</a>
                <!-- <a class="hidden font-medium text-gray-900 dark:text-gray-100 sm:block" href="#">Characters</a> -->
                <!-- <a class="hidden font-medium text-gray-900 dark:text-gray-100 sm:block" href="#">Topics</a> -->
                <a class="hidden font-medium text-gray-900 dark:text-gray-100 sm:block" href="{{ route('contact') }}">Contact</a>

                <div class="hidden text-gray-900 dark:text-gray-100 sm:block relative text-left dropdown">
                    <button
                        class="inline-flex justify-center w-full font-medium transition duration-150 ease-in-out"
                        type="button"
                        aria-haspopup="true"
                        aria-expanded="true"
                        aria-controls="links-dropdown"
                    >
                        <span id="links-dropdown-label">Links</span>
                        <svg class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="hidden dropdown-menu">
                        <div
                            class="absolute right-0 w-40 mt-2 origin-top-right bg-white dark:bg-gray-950 divide-y divide-gray-100 dark:divide-slate-50 rounded-md shadow-lg dark:shadow-sm dark:shadow-slate-50 outline-none"
                            aria-labelledby="links-dropdown-label"
                            id="links-dropdown"
                            role="menu"
                        >
                            @php /** @var \App\Domains\Blog\Models\LinkCategory $linkCategory */@endphp
                            @foreach($linkCategories as $linkCategory)
                                <a
                                    href="{{ route('link.show', $linkCategory->slug) }}"
                                    class="flex justify-between w-full px-4 py-2 text-left" role="menuitem"
                                >
                                    {{ $linkCategory->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button :aria-label="`toggle ${currentMode} mode`"
                        x-data="{
                            currentMode: null,
                            mode() {
                                if (document.documentElement.classList.contains('dark')) {
                                    return 'dark';
                                }

                                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                                    return 'dark';
                                }

                                return 'light';
                            },
                            toggleMode() {
                                newMode = this.mode() === 'light' ? 'dark' : 'light';
                                localStorage.theme = newMode;

                                document.documentElement.classList.remove('dark', 'light');
                                document.documentElement.classList.add(newMode);
                                this.currentMode = newMode;
                            },
                            init() {
                                this.currentMode = this.mode();
                            }
                        }"
                        @click="toggleMode"
                        x-cloak
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20"
                         fill="currentColor"
                         class="h-6 w-6 text-gray-900 dark:text-gray-100"
                         x-show="currentMode == 'light'"
                    >
                        <path fill-rule="evenodd"
                              d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                              clip-rule="evenodd"></path>
                    </svg>
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor"
                         class="h-6 w-6 text-gray-900 dark:text-gray-100"
                         x-show="currentMode == 'dark'"
                    >
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>
                <button aria-label="Toggle Menu" class="sm:hidden" x-data="{}" @click="$dispatch('mobile-nav-toggle')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                         class="h-8 w-8 text-gray-900 dark:text-gray-100">
                        <path fill-rule="evenodd"
                              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                              clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div
                    x-data="{ showMobileNav: false }"
                    class="fixed left-0 top-0 z-10 h-full w-full transform bg-white opacity-95 duration-300 ease-in-out dark:bg-gray-950 dark:opacity-[0.98]"
                    :class="showMobileNav ? 'translate-x-0' : 'translate-x-full'"
                    @mobile-nav-toggle.window="showMobileNav = true; console.log('hey')"
                    x-cloak
                >
                    <div class="flex justify-end">
                        <button class="mr-8 mt-11 h-8 w-8" aria-label="Toggle Menu" @click="showMobileNav = false">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="text-gray-900 dark:text-gray-100">
                                <path fill-rule="evenodd"
                                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <nav class="fixed mt-8 h-full">
                        <div class="px-12 py-4">
                            <a class="text-2xl font-bold tracking-widest text-gray-900 dark:text-gray-100" href="{{ route('blog-post.index') }}">Home</a>
                        </div>
                        <div class="px-12 py-4">
                            <a class="text-2xl font-bold tracking-widest text-gray-900 dark:text-gray-100" href="{{ route('about') }}">About</a>
                        </div>
                        <div class="px-12 py-4">
                            <a class="text-2xl font-bold tracking-widest text-gray-900 dark:text-gray-100" href="{{ route('contact') }}">Contact</a>
                        </div>

                        @php /** @var \App\Domains\Blog\Models\LinkCategory $linkCategory */@endphp
                        @foreach($linkCategories as $linkCategory)
                            <div class="px-12 py-4">
                                <a class="text-2xl font-bold tracking-widest text-gray-900 dark:text-gray-100" href="{{ route('link.show', $linkCategory->slug) }}">
                                    {{ $linkCategory->label }}
                                </a>
                            </div>
                        @endforeach
                    </nav>
                </div>
            </div>
        </header>
        <main class="mb-auto">
            {{ $slot }}
        </main>
        <footer>
            <div class="mt-16 flex flex-col items-center">
                <div class="mb-3 flex space-x-4">
                    <a class="text-sm text-gray-500 transition hover:text-gray-600"
                                                    target="_blank" rel="noopener noreferrer"
                                                    href="mailto:nick@godless-internets.org"><span class="sr-only">mail</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="fill-current text-gray-700 hover:text-primary-500 dark:text-gray-200 dark:hover:text-primary-400 h-6 w-6">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                    </a>
                    <a class="text-sm text-gray-500 transition hover:text-gray-600" target="_blank"
                           rel="noopener noreferrer" href="https://github.com/nie7321"><span class="sr-only">github</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             class="fill-current text-gray-700 hover:text-primary-500 dark:text-gray-200 dark:hover:text-primary-400 h-6 w-6">
                            <path
                                d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"></path>
                        </svg>
                    </a>
                    <a class="text-sm text-gray-500 transition hover:text-gray-600" target="_blank"
                       rel="me" href="https://mastodon.yshi.org/@owls"><span class="sr-only">fediverse (mastodon)</span>
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="fill-current text-gray-700 hover:text-primary-500 dark:text-gray-200 dark:hover:text-primary-400 h-6 w-6">
                           <path d="M23.268 5.313c-.35-2.578-2.617-4.61-5.304-5.004C17.51.242 15.792 0 11.813 0h-.03c-3.98 0-4.835.242-5.288.309C3.882.692 1.496 2.518.917 5.127.64 6.412.61 7.837.661 9.143c.074 1.874.088 3.745.26 5.611.118 1.24.325 2.47.62 3.68.55 2.237 2.777 4.098 4.96 4.857 2.336.792 4.849.923 7.256.38.265-.061.527-.132.786-.213.585-.184 1.27-.39 1.774-.753a.057.057 0 0 0 .023-.043v-1.809a.052.052 0 0 0-.02-.041.053.053 0 0 0-.046-.01 20.282 20.282 0 0 1-4.709.545c-2.73 0-3.463-1.284-3.674-1.818a5.593 5.593 0 0 1-.319-1.433.053.053 0 0 1 .066-.054c1.517.363 3.072.546 4.632.546.376 0 .75 0 1.125-.01 1.57-.044 3.224-.124 4.768-.422.038-.008.077-.015.11-.024 2.435-.464 4.753-1.92 4.989-5.604.008-.145.03-1.52.03-1.67.002-.512.167-3.63-.024-5.545zm-3.748 9.195h-2.561V8.29c0-1.309-.55-1.976-1.67-1.976-1.23 0-1.846.79-1.846 2.35v3.403h-2.546V8.663c0-1.56-.617-2.35-1.848-2.35-1.112 0-1.668.668-1.67 1.977v6.218H4.822V8.102c0-1.31.337-2.35 1.011-3.12.696-.77 1.608-1.164 2.74-1.164 1.311 0 2.302.5 2.962 1.498l.638 1.06.638-1.06c.66-.999 1.65-1.498 2.96-1.498 1.13 0 2.043.395 2.74 1.164.675.77 1.012 1.81 1.012 3.12z"/></svg>
                    </a>
                    <a class="text-sm text-gray-500 transition hover:text-gray-600" target="_blank" href="{{ route('feed.atom') }}">
                        <span class="sr-only">RSS feed</span>
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                             class="fill-current text-gray-700 hover:text-primary-500 dark:text-gray-200 dark:hover:text-primary-400 h-6 w-6">
                        >
                            <path d="M19.199 24C19.199 13.467 10.533 4.8 0 4.8V0c13.165 0 24 10.835 24 24h-4.801zM3.291 17.415c1.814 0 3.293 1.479 3.293 3.295 0 1.813-1.485 3.29-3.301 3.29C1.47 24 0 22.526 0 20.71s1.475-3.294 3.291-3.295zM15.909 24h-4.665c0-6.169-5.075-11.245-11.244-11.245V8.09c8.727 0 15.909 7.184 15.909 15.91z"/>
                        </svg>
                    </a>
                </div>
                <div class="mb-4 flex flex-row flex-wrap justify-center gap-2 space-x-4 text-sm text-gray-500 dark:text-gray-400">
                    <div class="basis-full text-center md:basis-auto md:text-left">&copy; {{ \Illuminate\Support\Carbon::now()->year }} owls, all rights reserved</div>
                    <div><a href="{{ route('legal.credits') }}">credits</a></div>
                    <div><a href="{{ route('legal.terms') }}">terms of use &amp; privacy policy</a></div>
                    <div><a href="{{ route('filament.admin.pages.dashboard') }}">admin</a></div>
                </div>
                <div class="mb-4 flex flex-wrap justify-center content-center gap-4">
                    <img src="{{ asset('image/tfnow.gif') }}" alt="Team Fortress Now!">
                    <img src="{{ asset('image/php.gif') }}" alt="Powered by PHP">
                    <img src="{{ asset('image/petsites.gif') }}" alt="Top 100 Pet Sites">
                    <img src="{{ asset('image/internetprivacy.gif') }}" alt="Internet Priavy Now">
                </div>
                <div class="mb-8">
                    <a href="https://yshi.org" target="_blank">
                        <img src="{{ asset('image/yasashii_badge.png') }}" alt="Yasashii Syndicate">
                    </a>
                </div>
            </div>
        </footer>
    </div>
</section>

@include('layouts._lightbox')
@stack('modals')
@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
