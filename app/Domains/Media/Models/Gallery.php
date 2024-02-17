<?php

namespace App\Domains\Media\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    public function gallery_images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('order_index', 'asc');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
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
