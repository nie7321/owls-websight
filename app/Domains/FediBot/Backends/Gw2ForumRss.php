<?php

namespace App\Domains\FediBot\Backends;

use App\Domains\FediBot\Backends\Concerns\RssTools;
use App\Domains\FediBot\Entities\ServerLimits;
use App\Domains\FediBot\Entities\Post;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag as MessageBagImpl;

class Gw2ForumRss implements PostBackend
{
    use RssTools;

    public function validateConfiguration(array $configuration): MessageBag
    {
        $bag = new MessageBagImpl;
        $bag->addIf(! Arr::has($configuration, 'feed'), 'feed', "Key 'feed' expected with URL");

        return $bag;
    }

    /**
     * @return Collection<Post>
     */
    public function postsFromFeed(array $configuration, ServerLimits $limits): Collection
    {
        $feedUrl = Arr::get($configuration, 'feed');
        throw_unless($feedUrl, new \Exception('Bad config (todo make err better'));

        $xml = simplexml_load_string($this->getFeed($feedUrl));

        $posts = [];
        foreach ($xml->xpath('/rss/channel/item') as $item) {
            $posts[] = $this->itemToPost($item, $limits);
        }

        return (new Collection($posts))->reverse();
    }
}
