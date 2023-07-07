<?php

namespace App\Domains\FediBot\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Bot extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'access_token' => 'encrypted',
        'configuration' => 'array',
    ];

    public function backend(): BelongsTo
    {
        return $this->belongsTo(BotBackend::class);
    }

    public function post_history(): HasMany
    {
        return $this->hasMany(PostHistory::class);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function domain(): Attribute
    {
        // @TODO this would have to webfinger to be accurate tho
        return Attribute::make(
            get: fn () => Str::of($this->username)->after('@')->prepend('https://'),
        );
    }
}