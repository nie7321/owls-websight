<?php

namespace App\Domains\Blog\Enums;

enum PublishingStatus
{
    case DRAFT;
    case SCHEDULED;
    case PUBLISHED;
}
