<?php

namespace App\Domains\Blog\Models;

use App\Domains\Blog\Enums\LinkCategoryEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'slug' => LinkCategoryEnum::class,
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('defaultOrder', function (Builder $builder) {
            $builder->orderBy('order_index')->orderBy('label');
        });
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }
}
