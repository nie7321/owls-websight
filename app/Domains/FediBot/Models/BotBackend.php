<?php

namespace App\Domains\FediBot\Models;

use App\Domains\FediBot\Enums\BackendType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotBackend extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'type' => BackendType::class,
    ];
}
