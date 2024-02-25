<?php

namespace App\Domains\Blog\Seeders;

use App\Domains\Blog\Models\RelationshipType;
use App\Domains\Foundation\Seeders\IdempotentSeeder;
use Illuminate\Support\Str;

class RelationshipTypeSeeder extends IdempotentSeeder
{
    protected string $model = RelationshipType::class;
    protected string $slugColumn = 'slug';

    public function data(): array
    {
        $xfnValues = [
            'acquaintance',
            'contact',
            'friend',
            'met',
            'co-worker',
            'colleague',
            'co-resident',
            'neighbor',
            'child',
            'kin',
            'parent',
            'sibling',
            'spouse',
            'muse',
            'crush',
            'date',
            'sweetheart',
        ];

        return collect($xfnValues)->map(function (string $slug): array {
            return [
                'slug' => $slug,
                'label' => Str::ucwords($slug),
            ];
        })->all();
    }
}

