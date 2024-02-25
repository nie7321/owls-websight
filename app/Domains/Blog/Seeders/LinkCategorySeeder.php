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
        return collect(LinkCategoryEnum::cases())
            ->map(function (LinkCategoryEnum $case) {
                return [
                    'slug' => $case,
                    'label' => LinkCategoryEnum::label($case),
                ];
            })
            ->all();
    }
}
