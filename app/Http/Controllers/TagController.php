<?php

namespace App\Http\Controllers;

use App\Domains\Blog\Markdown\PostRenderer;
use App\Domains\Blog\Models\Tag;
use App\Domains\Blog\Repositories\BlogPostRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function show(Request $request, PostRenderer $markdown, string $tagSlug): View
    {
        $tag = Tag::whereSlug(Str::lower($tagSlug))->firstOrFail();
        $posts = $tag->blog_posts()->forDisplay()->paginate(10);

        return view('tag.show', [
            'tag' => $tag,
            'toMarkdown' => $markdown,
            'posts' => $posts,
        ]);
    }
}
