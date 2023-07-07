<?php

namespace Database\Seeders;

use App\Domains\FediBot\Seeders\BotBackendSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BotBackendSeeder::class);
    }
}
