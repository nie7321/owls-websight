<?php

declare(strict_types=1);

namespace App\Domains\RssDiscovery\Actions;

use App\Domains\RssDiscovery\Entities\DiscoveredFeed;
use App\Domains\RssDiscovery\Entities\FeedDiscoveryResult;
use App\Domains\RssDiscovery\Types\FeedType;
use Dom\HTMLCollection;
use Dom\HTMLDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Uri;

class DiscoverSiteFeed
{
    public function for(?string $siteName, string $url): FeedDiscoveryResult
    {
        $response = retry(3, fn () => Http::get($url)->throw(), 250);

        $doc = @HTMLDocument::createFromString($response->body());
        $linkTags = $doc->getElementsByTagName('link');

        $feeds = $this->getFeeds($url, $linkTags);

        return new FeedDiscoveryResult(
            siteName: $siteName,
            siteUrl: $url,
            feeds: $feeds,
        );
    }

    private function getFeeds(string $siteBaseUrl, HTMLCollection $elements): Collection
    {
        $feeds = [];

        foreach ($elements as $link) {
            // These should always be alts
            if ($link->getAttribute('rel') !== 'alternate') {
                continue;
            }

            $typeRaw = $link->getAttribute('type');
            if (! $typeRaw) {
                continue;
            }

            $type = FeedType::tryFrom($typeRaw);
            if ($type === null) {
                continue;
            }

            $url = $link->getAttribute('href');
            if (! str_starts_with($url, 'http')) {
                $url = (string) Uri::of($siteBaseUrl)->withPath($url);
            }

            $feeds[] = new DiscoveredFeed(
                title: $link->getAttribute('title'),
                type: $type,
                url: $url,
            );
        }

        return collect($feeds);
    }
}
