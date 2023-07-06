<?php

namespace App\Domains\FediBot\Entity;

class ServerLimits
{
    public readonly int $postMaxCharacters;
    public readonly int $linkCharacterCount;
    public readonly int $maxAttachments;

    /**
     * @param int $linkCharacterCount Mastodon counts any URL as a fixed number of characters.
     */
    public function __construct(?int $postMaxCharacters, ?int $linkCharacterCount, ?int $maxAttachments)
    {
        $this->postMaxCharacters = $postMaxCharacters ?? 500;
        $this->linkCharacterCount = $linkCharacterCount ?? 23;
        $this->maxAttachments = $maxAttachments ?? 4;
    }
}
