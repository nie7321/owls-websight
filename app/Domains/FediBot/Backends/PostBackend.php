<?php

namespace App\Domains\FediBot\Backends;

use App\Domains\FediBot\Entities\Post;
use App\Domains\FediBot\Entities\ServerLimits;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Collection;

interface PostBackend
{
    public function validateConfiguration(array $configuration): MessageBag;

    /**
     * @return Collection<Post>
     */
    public function postsFromFeed(array $configuration, ServerLimits $limits): Collection;
}
