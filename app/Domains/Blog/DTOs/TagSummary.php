<?php

declare(strict_types=1);

namespace App\Domains\Blog\DTOs;

readonly class TagSummary
{
    public function __construct(
        public string $slug,
        public string $label,
        public int $postCount,
    ) {
        //
    }
}
