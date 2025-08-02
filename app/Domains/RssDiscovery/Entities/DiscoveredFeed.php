<?php

declare(strict_types=1);

namespace App\Domains\RssDiscovery\Entities;

use App\Domains\RssDiscovery\Types\FeedType;
use SimpleXMLElement;

readonly class DiscoveredFeed
{
    public string $url;
    public int $score;

    public function __construct(
        public ?string $title,
        public FeedType $type,
        string $url,
    ) {
        $score = match ($this->type) {
            FeedType::ATOM => 2,
            FeedType::RSS => 1,
        };

        if (str_contains((string) $this->title, 'Comments Feed')) {
            $score -= 10;
        }

        $this->score = $score;
        $this->url = $url;
    }

    public function opmlOutline(?string $title, string $htmlUrl): string
    {
        // Who knows what kinda weird-ass characters people will put in their titles. Let SimpleXML encode that stuff.
        $element = new SimpleXMLElement('<outline type="rss"/>');
        $element->addAttribute('text', $title ?? $this->title ?? $htmlUrl);
        $element->addAttribute('xmlUrl', $this->url);
        $element->addAttribute('htmlUrl', $htmlUrl);

        // SimpleXML wants to return <?xml ...>\n<outline .../>, which is not what I want here.
        // Do a light String Crime to fix it.
        return explode("\n", $element->asXml())[1];
    }
}
