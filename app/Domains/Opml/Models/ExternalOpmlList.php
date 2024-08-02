<?php

namespace App\Domains\Opml\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalOpmlList extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return Attribute<string, never>
     */
    protected function opmlDiskPath(): Attribute
    {
        return Attribute::make(
            get: fn () => "opml/{$this->output_filename}",
        );
    }

    /**
     * @return Attribute<string, never>
     */
    protected function republishedUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset("storage/{$this->opmlDiskPath}"),
        );
    }
}
