@php /** @var \App\Domains\OpenGraph\Actions\DefaultCardSettings $settings */ @endphp
<svg
    viewBox="0 0 544 306"
    width="544"
    height="306"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
>
    <style>
        path {
            transform-origin: {{ $settings->waveOffset }}px;
        }
        text {
            text-anchor: middle;
            font-size: {{ $settings->textSizeRem }}rem;
            fill: {{ $settings->textFill }};
            stroke: {{ $settings->textStroke }};
            font-family: "{{ $settings->font->value }}", sans-serif;
        }
    </style>

    <rect x="0" y="0" width="544" height="306" fill="{{ $settings->bgColour }}"></rect>
    <path @unless($settings->waveBottom) transform="translate(1800), scale(-1, 1)" @endif fill="#0099ff" fill-opacity="0.2" d="M0,128L48,160C96,192,192,256,288,277.3C384,299,480,277,576,245.3C672,213,768,171,864,144C960,117,1056,107,1152,128C1248,149,1344,203,1392,229.3L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
    <text x="272" y="153">
        {{ $settings->title }}
    </text>
</svg>
