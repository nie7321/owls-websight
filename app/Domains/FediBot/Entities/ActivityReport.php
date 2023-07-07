<?php

namespace App\Domains\FediBot\Entities;

use App\Domains\FediBot\Models\Bot;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class ActivityReport implements Arrayable
{
    public function __construct(
        protected readonly Bot $bot,
        protected readonly Collection $newPosts,
        protected readonly Collection $allPostsFromBackend,
    )
    {
        //
    }

    public function toArray(): array
    {
        return [
            'bot' => [
                'id' => $this->bot->id,
                'name' => $this->bot->username,
                'nextCheck' => $this->bot->next_check_at,
            ],
            'newPosts' => [
                'count' => $this->newPosts->count(),
                'id' => $this->newPosts->map->id,
                'backendId' => $this->newPosts->map->identifier,
            ],
            'allPostsInBackend' => [
                'count' => $this->allPostsFromBackend->count(),
                'id' => $this->allPostsFromBackend->map->uniqueIdentifier,
            ],
        ];
    }
}
