<?php

namespace App\Domains\FediBot\Adapters;

use App\Domains\FediBot\Entities\ServerLimits;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Revolution\Mastodon\MastodonClient as RevolutionMastodonClient;
use Revolution\Mastodon\Contracts\Factory as RevolutionMastodonClientInterface;

class MastodonClient extends RevolutionMastodonClient implements RevolutionMastodonClientInterface
{
    public function limits(): ServerLimits
    {
        $cacheKey = "FEDI_LIMITS/{$this->domain}";

        return Cache::remember($cacheKey, CarbonInterval::minutes(15)->totalSeconds, $this->fetchLimits(...));
    }

    private function fetchLimits(): ServerLimits
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
