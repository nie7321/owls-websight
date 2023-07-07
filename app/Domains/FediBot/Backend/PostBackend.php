<?php

namespace App\Domains\FediBot\Backend;

use App\Domains\FediBot\Entity\Post;
use App\Domains\FediBot\Entity\ServerLimits;
use Illuminate\Support\Collection;

interface PostBackend
{
    /**
     * @return Collection<Post>
     */
    public function postsFromFeed(array $configuration, ServerLimits $limits): Collection;
}
