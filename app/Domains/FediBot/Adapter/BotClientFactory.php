<?php

namespace App\Domains\FediBot\Adapter;

use App\Domains\FediBot\Models\Bot;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class BotClientFactory
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
}
