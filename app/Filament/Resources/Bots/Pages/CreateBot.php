<?php

namespace App\Filament\Resources\Bots\Pages;

use App\Domains\FediBot\Models\Bot;
use App\Domains\FediBot\PostingService;
use App\Filament\Resources\Bots\BotResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBot extends CreateRecord
{
    protected static string $resource = BotResource::class;

    protected function afterCreate(): void
    {
        /** @var Bot $bot */
        $bot = $this->record;

        /** @var PostingService $postingService */
        $postingService = resolve(PostingService::class);

        $postingService->initializeWithoutPosting($bot);
    }
}
