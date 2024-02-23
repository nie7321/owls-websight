<?php

namespace App\Domains\Blog\Policies;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Models\BlogPost;

class BlogPostPolicy
{
    public function preview(User $user, BlogPost $post): bool
    {
        return $user->is_admin;
    }
}
