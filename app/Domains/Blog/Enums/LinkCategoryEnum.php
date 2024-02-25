<?php

namespace App\Domains\Blog\Enums;

enum LinkCategoryEnum: string
{
    case BLOG_ROLL = 'blog-roll';
    case OTHER = 'other';

    public static function label(self $case): string
    {
        return match ($case) {
            self::BLOG_ROLL => 'Blog Roll',
            self::OTHER => 'Interesting Links',
        };
    }
}
