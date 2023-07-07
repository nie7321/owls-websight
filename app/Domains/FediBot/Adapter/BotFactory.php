<?php

namespace App\Domains\FediBot\Adapter;

use App\Domains\FediBot\Backend\Gw2ForumRss;
use App\Domains\FediBot\Backend\PostBackend;
use App\Domains\FediBot\Enum\BackendType;
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
            ->domain($bot->domain)
            ->token($bot->access_token);
    }

    public function toBackend(Bot $bot): PostBackend
    {
        return match($bot->backend->type) {
            BackendType::GW2_FORUM_RSS => resolve(Gw2ForumRss::class),
            default => throw UnknownBackend::for($bot->backend->type)
        };
    }
}
