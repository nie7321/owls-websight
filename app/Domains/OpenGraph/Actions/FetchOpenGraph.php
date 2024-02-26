<?php

namespace App\Domains\OpenGraph\Actions;

use App\Domains\OpenGraph\Entities\OpenGraphMetadata;
use App\Domains\OpenGraph\OpenGraphSpider;
use Illuminate\Support\Arr;
use shweshi\OpenGraph\OpenGraph;

class FetchOpenGraph
{
    public function __construct(
        protected OpenGraph $openGraph,
    )
    {
        //
    }

    public function fetch(string $url): ?OpenGraphMetadata
    {
        $data = retry(
            times: 3,
            callback: fn () => $this->openGraph->fetch($url, userAgent: OpenGraphSpider::USER_AGENT),
            sleepMilliseconds: 250
        );

        if (! $data || ! Arr::get($data, 'title')) {
            return null;
        }

        return new OpenGraphMetadata(
            siteName: Arr::get($data, 'site_name'),
            title: Arr::get($data, 'title'),
            description: Arr::get($data, 'description'),
            imageUrl: Arr::get($data, 'image:secure_url', Arr::get($data, 'image')),
            type: Arr::get($data, 'type'),
        );
    }
}
