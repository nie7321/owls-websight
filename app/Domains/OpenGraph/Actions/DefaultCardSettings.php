<?php

namespace App\Domains\OpenGraph\Actions;

use App\Domains\OpenGraph\Enums\DefaultCardFont;

readonly class DefaultCardSettings
{
    public string $seed;
    public string $textFill;
    public string $textStroke;
    public string $bgColour;
    public DefaultCardFont $font;
    public float $textSizeRem;
    public int $waveOffset;
    public bool $waveBottom;

    public function __construct(
        public string $title,
        ?int $seed = null,
    )
    {
        $this->seed = $seed ?? crc32($this->title);
        [$this->bgColour, $this->textFill, $this->textStroke] = $this->colourPalette();
        $this->font = $this->font();
        $this->textSizeRem = $this->calculateTextSize();
        $this->waveOffset = ($this->seed % 25 * 10) * -1;
        $this->waveBottom = $this->seed % 2 === 1;
    }

    private function calculateTextSize(): float
    {
        $fontSizeRem = 3.5;
        $magicStrlenToStartResizingAt = 16;
        $strlen = strlen($this->title);

        return $fontSizeRem - ($strlen - $magicStrlenToStartResizingAt) * .15;
    }

    private function font(): DefaultCardFont
    {
        return collect(DefaultCardFont::cases())->shuffle($this->seed)->first();
    }

    private function colourPalette(): array
    {
        return collect([
            ['#FFFFFF', '#333333', '#000000'],
            ['#F0F0F0', '#444444', '#000000'],
            ['#FFCC66', '#333333', '#000000'],
            ['#6699CC', '#FFFFFF', '#000000'],
            ['#FF6699', '#FFFFFF', '#000000'],
            ['#CC99FF', '#333333', '#000000'],
            ['#99CC99', '#333333', '#000000'],
            ['#FF9966', '#FFFFFF', '#000000'],
            ['#CCCCFF', '#333333', '#000000'],
            ['#66CCCC', '#FFFFFF', '#000000'],
            ['#FFFF99', '#333333', '#000000'],
            ['#FF99CC', '#FFFFFF', '#000000'],
            ['#CCFF66', '#333333', '#000000'],
            ['#FFCC99', '#333333', '#000000'],
            ['#99CCCC', '#333333', '#000000'],
            ['#FFCCCC', '#333333', '#000000'],
            ['#CCCC66', '#333333', '#000000'],
            ['#CCFFCC', '#333333', '#000000'],
            ['#FF99FF', '#333333', '#000000'],
            ['#99FFCC', '#333333', '#000000'],
        ])->shuffle($this->seed)->first();
    }
}
