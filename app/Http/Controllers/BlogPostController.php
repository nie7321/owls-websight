<?php

namespace App\Http\Controllers;

use App\Domains\Blog\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlogPostController extends Controller
{
    public function __invoke(Request $request, int $year, int $month, int $day, string $slug)
    {
        $urlDate = Carbon::create($year, $month, $day);
        $post = BlogPost::query()
            ->with(['tags', 'author'])
            ->whereSlug(strtolower($slug))
            ->firstOrFail();

        abort_unless($post->published_at, 403);
        abort_unless($post->published_at->isSameDay($urlDate), 404);
        abort_unless($post->published_at->lessThan(Carbon::now()), 403);

        dd($post->title, $post->content, $post->tags->map->label);
    }
}