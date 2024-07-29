@php /** @var \App\Domains\Media\Models\Gallery $gallery */ @endphp
<figure id="gallery-{{ $slug }}" class="not-prose" x-data="{}">
    <ul class="flex flex-wrap list-none">
    @foreach ($gallery->images as $image)
        <li class="mr-4 w-[45%] flex flex-col flex-grow justify-center mb-4">
            <figure
                class="flex justify-end content-start h-full w-full max-w-none relative cursor-pointer"
                data-lightbox-image-src="{{ $image->getFirstMedia()->getUrl() }}"
                data-lightbox-caption="{{ $captionRenderer->convert($image->caption ?? "") }}"
                data-lightbox-alt="{{ $image->alt_description }}"
                x-on:click.stop="$dispatch('img-modal', {
                    imgModalSrc: $el.dataset.lightboxImageSrc,
                    imgModalAlt: $el.dataset.lightboxAlt,
                    imgModalDesc: $el.dataset.lightboxCaption,
                })"
            >
                <img
                    class="block object-cover h-full w-full"
                    src="{{ $image->getFirstMedia()->getUrl() }}"
                    title="{{ $image->title }}"
                    alt="{{ $image->alt_description }}"
                >
                @if ($image->caption)
                    <figcaption class="bg-gallery-caption-text line-clamp-3 text-white absolute bottom-0 text-center z-10 w-full pt-12 pb-3 px-3">
                        <div class="line-clamp-3">
                            {!! $captionRenderer->convert($image->caption) !!}
                        </div>
                    </figcaption>
                @endif
            </figure>
        </li>
    @endforeach
    </ul>
</figure>
