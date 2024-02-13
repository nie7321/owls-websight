<?php

namespace App\Domains\FediBot\Entities;

use Carbon\CarbonInterface;

class Post
{
    public function __construct(
        public readonly string $uniqueIdentifier,
        public readonly string $formattedMessage,
        public readonly CarbonInterface $publishedAt,
        public readonly ?array $attachments = null,
    )
    {
        //
    }
}
