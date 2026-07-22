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
        $publishedPostSubquery = DB::query()
            ->from('blog_post_tag')
            ->join('blog_posts', 'blog_posts.id', '=', 'blog_post_tag.blog_post_id')
            ->whereNotNull('published_at');

        // Gunna show them all at once, so be efficient here.
        $tags = DB::query()
            ->select([
                'tags.label',
                'tags.slug',
                DB::raw('COUNT(posts.*) as post_count')
            ])
            ->from((new Tag)->getTable())
            ->leftJoinSub($publishedPostSubquery, 'posts', 'posts.tag_id', '=', 'tags.id')
            ->groupBy('tags.label', 'tags.slug')
            ->orderBy('post_count', 'desc')
            ->orderBy('tags.label')
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
