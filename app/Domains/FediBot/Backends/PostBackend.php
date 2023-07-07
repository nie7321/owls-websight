<?php

namespace App\Domains\FediBot\Backends;

use App\Domains\FediBot\Entities\Post;
use App\Domains\FediBot\Entities\ServerLimits;
use Illuminate\Support\Collection;

interface PostBackend
{
    /**
     * @return Collection<Post>
     */
    public function postsFromFeed(array $configuration, ServerLimits $limits): Collection;
}
