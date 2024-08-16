<svg xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <filter id="inset-shadow" x="-50%" y="-50%" width="200%" height="200%">
        <feComponentTransfer in="SourceAlpha">
            <feFuncA type="table" tableValues="1 0" />
        </feComponentTransfer>
        <feGaussianBlur stdDeviation="1"/>
        <feOffset dx="-4" dy="-7" result="offsetblur"/>
        <feFlood flood-color="rgb(0, 0, 0, 0.5)" result="color"/>
        <feComposite in2="offsetblur" operator="in"/>
        <feComposite in2="SourceAlpha" operator="in" />
        <feMerge>
            <feMergeNode in="SourceGraphic" />
            <feMergeNode />
        </feMerge>
    </filter>

    <g>
        <line x1="79" y1="170" x2="79" y2="400" stroke="grey"/>
        <ellipse cx="80" cy="100" rx="60" ry="72.5" filter="url(#inset-shadow)"/>
        <polygon class="tip" points="79 165, 71 178, 86 178" filter="brightness(87%)"/>
    </g>
</svg>
