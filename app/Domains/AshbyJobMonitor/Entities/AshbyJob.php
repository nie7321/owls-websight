<?php

declare(strict_types=1);

namespace App\Domains\AshbyJobMonitor\Entities;

use Carbon\CarbonInterface;

readonly class AshbyJob
{
    public function __construct(
        public string $uuid,
        public CarbonInterface $publishedAt,
        public string $title,
        public string $postUrl,
        public string $applyUrl,
        public string $htmlDescription,
        public string $plainDescription,
    )
    {
        //
    }
}
