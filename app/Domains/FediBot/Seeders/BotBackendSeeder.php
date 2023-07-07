<?php

namespace App\Domains\FediBot\Seeders;

use App\Domains\FediBot\Enums\BackendType;
use App\Domains\FediBot\Models\BotBackend;
use App\Domains\Foundation\Seeders\IdempotentSeeder;

class BotBackendSeeder extends IdempotentSeeder
{
    protected string $model = BotBackend::class;
    protected string $slugColumn = 'type';

    public function data(): array
    {
        return [
            ['type' => BackendType::GW2_FORUM_RSS, 'label' => 'Guild Wars 2 Forum RSS'],
        ];
    }
}
