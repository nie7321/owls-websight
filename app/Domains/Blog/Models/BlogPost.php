<?php

namespace App\Domains\Blog\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PostStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
     * @return Attribute<PostStatus, never>
     */
    public function status(): Attribute
    {
        return Attribute::make(
            get: function(): PostStatus {
                if (! $this->published_at) {
                    return PostStatus::DRAFT;
                }

                $now = Carbon::now();
                if ($this->published_at->lte($now)) {
                    return PostStatus::PUBLISHED;
                }

                return PostStatus::SCHEDULED;
            },
        );


    }
}
