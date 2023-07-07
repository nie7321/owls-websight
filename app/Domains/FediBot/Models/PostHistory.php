<?php

namespace App\Domains\FediBot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHistory extends Model
{
    use HasFactory;

    protected $table = 'bot_post_history';
}
