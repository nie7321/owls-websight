<?php

namespace App\Domains\Blog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * XHTML Friends Network (XFN) relationship types for links.
 *
 * @see https://gmpg.org/xfn/
 * @see https://codex.wordpress.org/Defining_Relationships_with_XFN
 */
class RelationshipType extends Model
{
    use HasFactory, SoftDeletes;
}
