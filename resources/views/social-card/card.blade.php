@php /** @var \App\Domains\OpenGraph\Actions\DefaultCardSettings $settings */ @endphp
<svg
    viewBox="0 0 544 306"
    width="544"
    height="306"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
>
    <style>
        text {
            text-anchor: middle;
            font-size: {{ $settings->textSizeRem }}rem;
            fill: {{ $settings->textFill }};
            stroke: {{ $settings->textStroke }};
            font-family: "{{ $settings->font->value }}", sans-serif;
        }
    </style>

    <rect x="0" y="0" width="544" height="306" fill="{{ $settings->bgColour }}"></rect>
    <text x="272" y="153">
        {{ $settings->title }}
    </text>
</svg>
