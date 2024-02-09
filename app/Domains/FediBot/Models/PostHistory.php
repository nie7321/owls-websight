<?php

namespace App\Domains\FediBot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bot_post_history';
}
