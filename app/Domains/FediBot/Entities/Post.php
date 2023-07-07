<?php

namespace App\Domains\FediBot\Entities;

class Post
{
    public function __construct(
        public readonly string $uniqueIdentifier,
        public readonly string $formattedMessage,
        public readonly ?array $attachments = null,
    )
    {
        //
    }
}
