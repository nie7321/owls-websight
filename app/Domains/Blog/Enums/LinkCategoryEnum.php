<?php

namespace App\Domains\Blog\Enums;

enum LinkCategoryEnum: string
{
    case BLOG_ROLL = 'blog-roll';
    case PODCASTS = 'podcasts';
    case OTHER = 'other';
}
