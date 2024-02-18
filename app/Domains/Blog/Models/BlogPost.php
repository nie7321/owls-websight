<?php

namespace App\Domains\Blog\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use App\Domains\Media\Models\Image;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public function thumbnail_image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'thumbnail_image_id');
    }

    /**
     * Scope to load publish posts, in order, with the relevant relationships loaded for display on the index/feed/etc.
     */
    public function scopeForDisplay(Builder $query): void
    {
        $query->published()->withRenderRelationships()->orderBy('published_at', 'desc');
    }

    public function scopeWithRenderRelationships(Builder $query): void
    {
        $query->with(['author', 'tags', 'thumbnail_image.media']);
    }

    public function scopePublished(Builder $query, ?CarbonInterface $now = null): void
    {
        $query->where('published_at', '<=', $now ?? Carbon::now());
    }

    /**
     * @return Attribute<string, never>
     */
    public function permalink(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (! $this->published_at) {
                    return null;
                }

                return route('blog-post.show', [
                    'year' => $this->published_at->year,
                    'month' => $this->published_at->format('m'),
                    'day' => $this->published_at->format('d'),
                    'slug' => $this->slug,
                ]);
            },
        );
    }

    /**
     * @return Attribute<PublishingStatus, never>
     */
    public function status(): Attribute
    {
        return Attribute::make(
            get: function(): PublishingStatus {
                if (! $this->published_at) {
                    return PublishingStatus::DRAFT;
                }

                $now = Carbon::now();
                if ($this->published_at->lte($now)) {
                    return PublishingStatus::PUBLISHED;
                }

                return PublishingStatus::SCHEDULED;
            },
        );


    }
}
