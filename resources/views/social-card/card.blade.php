<svg
    viewBox="0 0 544 306"
    width="544"
    height="306"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
>
    @php
    $fontSizeRem = 3.5;
    $magicStrlenToStartResizingAt = 16;
    $strlen = strlen($title);

    $fontSizeRem = $fontSizeRem - ($strlen - $magicStrlenToStartResizingAt) * .15;
    @endphp
    <style>
        text {
            text-anchor: middle;
            font-family: monospace;
            font-size: {{ $fontSizeRem }}rem;
            fill: {{ $textFill }};
            stroke: {{ $textStroke }};
        }
    </style>

    <rect x="0" y="0" width="544" height="306" fill="{{ $bgColour }}"></rect>
    <text x="272" y="153">
        {{ $title }}
    </text>
</svg>
