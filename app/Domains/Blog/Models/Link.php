<?php

namespace App\Domains\Blog\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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

    public function scopeReadyForRefresh(Builder $query, ?CarbonInterface $now = null): void
    {
        $now ??= Carbon::now();

        $query
            ->where('auto_update_card', true)
            ->where(function (Builder $query) use ($now): void {
                $query
                    ->whereNull('card_last_polled_at')
                    ->orWhere('card_last_polled_at', '<=', $now->copy()->subDay());
            })
            ->orderBy('card_last_polled_at');
    }

    /**
     * @return Attribute<string, never>
     */
    public function cardImageAssetPath(): Attribute
    {
        return Attribute::make(get: function () {
            if ($this->card_image_path) {
                return storage_path("storage/{$this->card_image_path}");
            }

            return route('social-card', ['title' => $this->title]);
        });
    }
}
