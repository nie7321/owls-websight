<?php

declare(strict_types=1);

namespace App\Domains\Webmention\Enums;

enum Mf2LinkType: string
{
    case LIKE = 'like';
    case COMMENT = 'comment';
    case REPOST = 'repost';

    public static function fromProperty(string $property): self
    {
        // rsvp intentionally not supported
        return match (strtolower($property)) {
            'like-of' => self::LIKE,
            'in-reply-to', 'mention-of' => self::COMMENT,
            'repost-of', 'bookmark-of' => self::REPOST,
            default => self::REPOST,
        };
    }
}
