<?php

namespace App\Domains\FediBot\Backends\Concerns;

use App\Domains\FediBot\Entities\Post;
use App\Domains\FediBot\Entities\ServerLimits;
use App\Domains\FediBot\Exceptions\PostBackendError;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait RssTools
{
    protected function itemToPost(SimpleXMLElement $item, ServerLimits $limits): Post
    {
        // +2 for the newlines we're going to add
        $linkCharacterCount = $limits->linkCharacterCount + 2;
        $truncateIndicator = ' ...';

        $pubDate = trim($item->pubDate);
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
            publishedAt: Carbon::createFromFormat(DATE_RFC2822, $pubDate)->utc(),
        );
    }

    protected function getFeed(string $url): string
    {
        $response = Http::retry(3, 250)
            ->accept('application/rss+xml')
            ->get($url);

        if (! $response->successful()) {
            throw new PostBackendError("Unable to get posts from GW2 forms: {$response->status()}}");
        }

        return $response->body();
    }
}
