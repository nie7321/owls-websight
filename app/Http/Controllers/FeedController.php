<?php

namespace App\Http\Controllers;

use App\Domains\Blog\Markdown\AtomPostRenderer;
use App\Domains\Blog\Markdown\SummaryRenderer;
use App\Domains\Blog\Models\BlogPost;
use App\Domains\Blog\Repositories\BlogPostRepository;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __invoke(Request $request, SummaryRenderer $summaryRenderer, AtomPostRenderer $postRenderer)
    {
        $posts = BlogPost::forDisplay()->limit(15)->get();

        return response()
            ->view('feed/atom', [
                'summaryRenderer' => $summaryRenderer,
                'postRenderer' => $postRenderer,
                'posts' => $posts,
            ])
            ->header('Content-Type', 'application/atom+xml');
    }
}
