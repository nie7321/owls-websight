<?php

namespace App\Domains\Media\Models;

use App\Domains\Blog\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryMedia extends BlogPost
{
    use HasFactory, SoftDeletes;

    public function gallery(): HasOne
    {
        return $this->hasOne(Gallery::class);
    }

    public function media(): HasOne
    {
        return $this->hasOne(Media::class);
    }
}
