@php
/** @var \App\Domains\Blog\Models\Link $link */
$xfnString = $link->relationships->map->slug->join(' ');
@endphp
<div class="md max-w-[544px] p-4 md:w-1/2">
    <div class="h-full  overflow-hidden rounded-md border-2 border-gray-200 border-opacity-60 dark:border-gray-700">
        <a target="_blank" href="{{ $link->url }}" aria-label="{{ $link->title }}" rel="{{ $xfnString }}">
            <img
                alt="{{ $link->title }}"
                loading="lazy"
                decoding="async"
                class="object-cover object-center md:h-36 lg:h-48"
                style="color: transparent"
                width="544"
                height="306"
                src="{{ $link->card_image_asset_path }}"
                >
        </a>
        <div class="p-6">
            <h2 class="mb-3 text-2xl font-bold leading-8 tracking-tight">
                <a target="_blank" href="{{ $link->url }}" class="underline" rel="{{ $xfnString }}">
                    {{ $link->title }}
                </a>
            </h2>
            <div class="prose mb-3 max-w-none text-gray-500 dark:text-gray-400">
                {{ $link->description }}
            </div>
        </div>
    </div>
</div>
