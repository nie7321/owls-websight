<?php

namespace App\Http\Controllers;

use App\Domains\Blog\Markdown\AtomPostRenderer;
use App\Domains\Blog\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __invoke(Request $request, BlogPostRepository $repo, AtomPostRenderer $markdownRenderer)
    {
        $posts = $repo->findPublishedPosts()->limit(15)->get();

        return response()
            ->view('feed/atom', [
                'renderer' => $markdownRenderer,
                'posts' => $posts,
            ])
            ->header('Content-Type', 'application/atom+xml');
    }
}
