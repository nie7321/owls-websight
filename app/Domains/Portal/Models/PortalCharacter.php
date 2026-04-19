<?php

namespace App\Domains\Portal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalCharacter extends Model
{
    use SoftDeletes, HasFactory;

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(PortalEpisode::class)
            ->withPivot('order_index', 'role_in_episode')
            ->withTimestamps();
    }
}
