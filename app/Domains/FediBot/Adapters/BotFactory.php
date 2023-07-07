<?php

namespace App\Domains\FediBot\Adapters;

use App\Domains\FediBot\Backends\Gw2ForumRss;
use App\Domains\FediBot\Backends\PostBackend;
use App\Domains\FediBot\Enums\BackendType;
use App\Domains\FediBot\Exceptions\UnknownBackend;
use App\Domains\FediBot\Models\Bot;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BotFactory
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = Http::retry(3, 250)->buildClient();
    }

    public function toClient(Bot $bot): MastodonClient
    {
        return (new MastodonClient($this->httpClient))
            ->domain($bot->server_url)
            ->token($bot->access_token);
    }

    public function toBackend(Bot $bot): PostBackend
    {
        $backend = match($bot->backend->type) {
            BackendType::GW2_FORUM_RSS => resolve(Gw2ForumRss::class),
            default => throw UnknownBackend::for($bot->backend->type)
        };

        $errors = $backend->validateConfiguration($bot->configuration);
        if ($errors->isNotEmpty()) {
            throw new PostBackendError("Invalid backend config: {$errors->first()}");
        }

        return $backend;
    }
}
