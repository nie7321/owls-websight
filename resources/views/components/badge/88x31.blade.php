@props(['image', 'alt', 'link' => null, 'colspan' => 1])
<div class="grid-cols-{{ $colspan }} my-auto">
    @if ($link) <a href="{{ $link }}"> @endif
        <img src="{{ $image }}" alt="{{ $alt }}" loading="lazy" class="badge-88x31">
    @if ($link) </a> @endif
</div>
