<?php

namespace App\Domains\FediBot\Enums;

enum BackendType: string
{
    case RSS = 'RSS Feed(s)';
    case GW2_FORUM_RSS = 'gw2-forum-rss';
}
