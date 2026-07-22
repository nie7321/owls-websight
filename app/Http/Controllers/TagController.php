<?php

namespace App\Http\Controllers;

use App\Domains\Blog\DTOs\TagSummary;
use App\Domains\Blog\Markdown\PostRenderer;
use App\Domains\Blog\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): View
    {
        // Gunna show them all at once, so be efficient here.
        $tags = DB::query()
            ->select([
                'label',
                'slug',
                DB::raw('COUNT(blog_post_tag.*) as post_count')
            ])
            ->from((new Tag)->getTable())
            ->leftJoin('blog_post_tag', 'blog_post_tag.tag_id', '=', 'tags.id')
            ->groupBy('label', 'slug')
            ->orderBy('post_count', 'desc')
            ->orderBy('label')
            ->get()
            ->map(function (object $item) {
                return new TagSummary(
                    $item->slug,
                    $item->label,
                    $item->post_count,
                );
            });

        return view('tag.index', [
            'tags' => $tags,
        ]);
    }

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
