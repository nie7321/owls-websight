<?php

declare(strict_types=1);

namespace App\Domains\RssDiscovery\Entities;

use Illuminate\Support\Collection;

readonly class FeedDiscoveryResult
{
    /**
     * @param Collection<DiscoveredFeed> $feeds
     */
    public function __construct(
        public ?string $siteName,
        public string $siteUrl,
        public Collection $feeds,
    ) {
        //
    }

    /**
     * Returns the "best" feed for the site.
     *
     * This has some logic to it -- WordPress sites usually have a post feed and a comments feed.
     * We want the post feed.
     */
    public function bestFeed(): ?DiscoveredFeed
    {
        return $this->feeds->sortByDesc('score')->first();
    }
}
