<?php

namespace App\Domains\Blog\Repositories;

use App\Domains\Blog\Models\BlogPost;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class BlogPostRepository
{
    public function findPublishedPosts(?CarbonInterface $now = null, int $pageSize = 10): LengthAwarePaginator
    {
        $now ??= Carbon::now();

        return BlogPost::query()
            ->with(['author', 'tags'])
            ->where('published_at', '<=', $now)
            ->orderBy('published_at', 'desc')
            ->paginate($pageSize);
    }
}
