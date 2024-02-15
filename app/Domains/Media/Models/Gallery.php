<?php

namespace App\Domains\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    public function gallery_media(): HasMany
    {
        return $this->hasMany(GalleryMedia::class)->orderBy('order_index', 'asc');
    }
}
