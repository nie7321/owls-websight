<?php

namespace App\Domains\FediBot\Backend;

use App\Domains\FediBot\Entity\ServerLimits;
use App\Domains\FediBot\Entity\Post;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use SimpleXMLElement;

class Gw2ForumRss implements PostBackend
{
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

        return new Collection($posts);
    }

    protected function getFeed(string $url): string
    {
        $response = Http::retry(3, 250)
            ->accept('application/rss+xml')
            ->get($url);

        if (! $response->successful()) {
            throw new \Exception('throw a better exception chief');
        }

        return $response->body();
    }

    protected function itemToPost(SimpleXMLElement $item, ServerLimits $limits): Post
    {
        // +2 for the newlines we're going to add
        $linkCharacterCount = $limits->linkCharacterCount + 2;
        $truncateIndicator = ' ...';

        $title = trim($item->title);
        $url = (string) $item->link;

        $description = Str::of($item->description)
            ->pipe(fn (string $subject) => preg_replace('/(?:\s*\n\s*){3,}/m', "\n\n", $subject))
            ->pipe(fn (string $subject) => preg_replace('/^[^\S\r\n]+/m', '', $subject))
            ->rtrim()
            ->ltrim()
            ->toString();

        $description = "{$title}\n\n{$description}";

        // +2 for the newlines that go with the link
        $postLength = Str::length($description) + $linkCharacterCount;
        $truncateTo = $limits->postMaxCharacters - (Str::length($truncateIndicator) + $linkCharacterCount);

        if ($postLength > $limits->postMaxCharacters) {
            $description = Str::limit($description, $truncateTo, $truncateIndicator);
        }

        $description = trim($description) . "\n\n{$url}";

        return new Post(
            uniqueIdentifier: $url,
            formattedMessage: $description,
        );
    }
}
