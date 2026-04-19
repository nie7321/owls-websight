<?php

namespace App\Domains\Portal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortalSeason extends Model
{
    use SoftDeletes, HasFactory;

    public function episodes(): HasMany
    {
        return $this->hasMany(PortalEpisode::class);
    }
}
