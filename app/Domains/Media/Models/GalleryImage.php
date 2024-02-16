<?php

namespace App\Domains\Media\Models;

use App\Domains\Blog\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryImage extends BlogPost
{
    use HasFactory, SoftDeletes;

    public function gallery(): HasOne
    {
        return $this->hasOne(Gallery::class);
    }

    public function image(): HasOne
    {
        return $this->hasOne(Image::class);
    }
}
