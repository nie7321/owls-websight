<?php

namespace App\Domains\Blog\Enums;

enum PostStatus
{
    case DRAFT;
    case SCHEDULED;
    case PUBLISHED;
}
