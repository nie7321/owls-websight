<?php

namespace App\Domains\FediBot\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bot extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'next_check_at' => 'datetime',
        'access_token' => 'encrypted',
        'configuration' => 'array',
    ];

    public function backend(): BelongsTo
    {
        return $this->belongsTo(BotBackend::class, 'bot_backend_id');
    }

    public function post_history(): HasMany
    {
        return $this->hasMany(PostHistory::class);
    }

    public function latest_post(): HasOne
    {
        return $this->hasOne(PostHistory::class)->latestOfMany();
    }
}
