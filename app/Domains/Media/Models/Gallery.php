<?php

namespace App\Domains\Media\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    /** For Filament, because attaching when ordering by a pivot is broken. */
    public function images_filament(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'gallery_image')
            ->withPivot('order_index');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class)
            ->withPivot('order_index')
            ->orderByPivot('order_index');
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
