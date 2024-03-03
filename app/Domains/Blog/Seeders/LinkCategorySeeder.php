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
            [
                'slug' => LinkCategoryEnum::BLOG_ROLL,
                'label' => 'Blog Roll',
                'description' => 'If you want to be listed on the blog roll, let me know!',
                'order_index' => 5
            ],
            [
                'slug' => LinkCategoryEnum::PODCASTS,
                'label' => 'Podcasts',
                'description' => 'These are podcasts that I enjoy.',
                'order_index' => 10,
            ],
            [
                'slug' => LinkCategoryEnum::OTHER,
                'label' => 'Interesting Links',
                'description' => 'You can find some of the best stuff on the internet here.',
                'order_index' => 15
            ],
        ];
    }
}
