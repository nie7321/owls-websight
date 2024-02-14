<?php

namespace App\Domains\Blog\Repositories;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Blog\Models\Tag;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogPostRepository
{
    public function findPublishedPosts(?CarbonInterface $now = null): Builder
    {
        $now ??= Carbon::now();

        return BlogPost::query()
            ->with(['author', 'tags'])
            ->where('published_at', '<=', $now)
            ->orderBy('published_at', 'desc');
    }

    public function findPublishedPostsForTag(Tag $tag, ?CarbonInterface $now = null): Builder
    {
        $now ??= Carbon::now();

        return $tag->blog_posts()
            ->with(['author', 'tags'])
            ->where('published_at', '<=', $now)
            ->orderBy('published_at', 'desc');
    }
}
