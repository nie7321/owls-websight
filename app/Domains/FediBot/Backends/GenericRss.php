<?php

namespace App\Domains\FediBot\Backends;

use App\Domains\FediBot\Backends\Concerns\RssTools;
use App\Domains\FediBot\Entities\Post;
use App\Domains\FediBot\Entities\ServerLimits;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag as MessageBagImpl;
use Illuminate\Support\Str;

class GenericRss implements PostBackend
{
    use RssTools;

    public function validateConfiguration(array $configuration): MessageBag
    {
        $bag = new MessageBagImpl;

        if (! Arr::has($configuration, 'feed')) {
            $bag->add('feed', "Key 'feed' expected");

            return $bag;
        }

        if (! is_array($configuration['feed']) || ! Arr::isList($configuration['feed'])) {
            $bag->add('feed', "Key 'feed' expected as array");

            return $bag;
        }

        foreach ($configuration['feed'] as $i => $url) {
            $bag->addIf(! Str::isUrl($url, protocols: ['http', 'https']), "feed.{$i}", 'Value should be a valid HTTP or HTTPS URL');
        }

        return $bag;
    }

    /**
     * @return Collection<Post>
     */
    public function postsFromFeed(array $configuration, ServerLimits $limits): Collection
    {
        $posts = new Collection;
        foreach (Arr::get($configuration, 'feeds', []) as $feedUrl) {
            $posts = $posts->concat($this->forFeed($feedUrl, $limits));
        }

        return $posts->sortByDesc('publishedAt');
    }

    /**
     * May not be sorted properly.
     *
     * Sorting is handled collectively for all feeds in {@see GenericRss::postsFromFeed()}.
     *
     * @return Collection<Post>
     */
    protected function forFeed(string $feedUrl, ServerLimits $limits): Collection
    {
        $xml = simplexml_load_string($this->getFeed($feedUrl));

        $posts = [];
        foreach ($xml->xpath('/rss/channel/item') as $item) {
            $posts[] = $this->itemToPost($item, $limits);
        }

        return new Collection($posts);
    }
}
