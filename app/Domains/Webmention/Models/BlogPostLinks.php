<?php

namespace App\Domains\Webmention\Models;

use App\Domains\Blog\Models\BlogPost;
use App\Domains\Webmention\Enums\Mf2LinkType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPostLinks extends Model
{
    /** @use HasFactory<\Database\Factories\Domains\Webmention\Models\BlogPostLinksFactory> */
    use HasFactory, SoftDeletes;

    protected $casts = [
        'mf2_type' => Mf2LinkType::class,
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(KnownDomain::class, 'known_domain_id');
    }

    public function blog_post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }
}
