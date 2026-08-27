<?php

namespace App\Domains\Webmention\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnownDomain extends Model
{
    /** @use HasFactory<\Database\Factories\Domains\Webmention\Models\KnownDomainFactory> */
    use HasFactory, SoftDeletes;

    public function blog_post_links(): HasMany
    {
        return $this->hasMany(BlogPostLinks::class, 'known_domain_id');
    }

    public function blog_post_mentions(): HasMany
    {
        return $this->hasMany(BlogPostMentions::class, 'known_domain_id');
    }
}
