<?php

declare(strict_types=1);

namespace App\Domains\RssDiscovery\Types;

enum FeedType: string
{
    case RSS = 'application/rss+xml';
    case ATOM = 'application/atom+xml';
}
