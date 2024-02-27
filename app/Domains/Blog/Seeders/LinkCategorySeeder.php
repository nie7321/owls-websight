<?php

namespace App\Domains\Blog\Seeders;

use App\Domains\Blog\Enums\LinkCategoryEnum;
use App\Domains\Blog\Models\LinkCategory;
use App\Domains\Foundation\Seeders\IdempotentSeeder;

class LinkCategorySeeder extends IdempotentSeeder
{
    protected string $model = LinkCategory::class;
    protected string $slugColumn = 'slug';

    public function data(): array
    {
        return [
            ['slug' => LinkCategoryEnum::BLOG_ROLL, 'label' => 'Blog Roll', 'description' => 'If you want to be listed on the blog roll, let me know!'],
            ['slug' => LinkCategoryEnum::OTHER, 'label' => 'Interesting Links', 'description' => 'You can find some of the best stuff on the internet here.'],
        ];
    }
}
