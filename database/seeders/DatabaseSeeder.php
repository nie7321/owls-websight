<?php

namespace Database\Seeders;

use App\Domains\Blog\Seeders\LinkCategorySeeder;
use App\Domains\Blog\Seeders\RelationshipTypeSeeder;
use App\Domains\FediBot\Seeders\BotBackendSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BotBackendSeeder::class);
        $this->call(RelationshipTypeSeeder::class);
        $this->call(LinkCategorySeeder::class);
    }
}
