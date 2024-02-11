<?php

namespace App\Http\Controllers;

use App\Domains\Blog\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        return view('blog.index');
    }

    public function show(Request $request, int $year, int $month, int $day, string $slug): View
    {
        $urlDate = Carbon::create($year, $month, $day);
        $post = BlogPost::query()
            ->with(['tags', 'author'])
            ->whereSlug(strtolower($slug))
            ->firstOrFail();

        abort_unless($post->published_at, 403);
        abort_unless($post->published_at->isSameDay($urlDate), 404);
        abort_unless($post->published_at->lessThan(Carbon::now()), 403);

        return view('blog.show', [
            'post' => $post,
        ]);
    }
}
