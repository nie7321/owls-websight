<?php

namespace App\Http\Controllers;

use App\Domains\Blog\Enums\LinkCategoryEnum;
use App\Domains\Blog\Models\LinkCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function show(LinkCategoryEnum $categorySlug): View
    {
        $category = LinkCategory::whereSlug($categorySlug)->sole();
        $links = $category->links()->with('relationships')->orderBy('title')->paginate(24);

        return view('link.show', [
            'category' => $category,
            'links' => $links,
        ]);
    }
}
