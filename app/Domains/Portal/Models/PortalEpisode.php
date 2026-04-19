<?php

namespace App\Domains\Portal\Models;

use App\Domains\Blog\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalEpisode extends Model
{
    use SoftDeletes, HasFactory;

    protected $casts = [
        'air_date' => 'date',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(PortalSeason::class, 'portal_season_id');
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(PortalCharacter::class)
            ->withPivot('order_index', 'role_in_episode')
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
