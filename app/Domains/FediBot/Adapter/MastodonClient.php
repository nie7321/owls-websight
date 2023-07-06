<?php

namespace App\Domains\FediBot\Adapter;

use App\Domains\FediBot\Entity\ServerLimits;
use Illuminate\Support\Arr;
use Revolution\Mastodon\MastodonClient as RevolutionMastodonClient;
use Revolution\Mastodon\Contracts\Factory as RevolutionMastodonClientInterface;

class MastodonClient extends RevolutionMastodonClient implements RevolutionMastodonClientInterface
{
    public function limits(): ServerLimits
    {
        $instance = $this->get('/instance');

        return new ServerLimits(
            postMaxCharacters: Arr::get($instance, 'configuration.statuses.max_characters'),
            linkCharacterCount: Arr::get($instance, 'configuration.statuses.characters_reserved_per_url'),
            maxAttachments: Arr::get($instance, 'configuration.statuses.max_media_attachments'),
        );
    }

    public function call(string $method, string $api, array $options = []): array
    {
        // Some kinda hack since we wanna use the client from Http::buildClient() & its retry middleware.
        $options = ['laravel_data' => [], ...$options];

        return parent::call($method, $api, $options);
    }
}
