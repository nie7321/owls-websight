<?php

namespace App\Domains\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'card_last_polled_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LinkCategory::class, 'link_category_id');
    }

    public function relationships(): BelongsToMany
    {
        return $this->belongsToMany(RelationshipType::class);
    }
}
