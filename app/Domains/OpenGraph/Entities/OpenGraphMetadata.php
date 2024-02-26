<?php

namespace App\Domains\OpenGraph\Entities;

readonly class OpenGraphMetadata
{
    public function __construct(
        public ?string $siteName,
        public string $title,
        public ?string $description,
        public ?string $imageUrl,
        public ?string $type,
    )
    {
        //
    }
}
