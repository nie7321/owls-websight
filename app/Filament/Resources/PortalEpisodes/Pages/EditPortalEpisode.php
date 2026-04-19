<?php

namespace App\Filament\Resources\PortalEpisodes\Pages;

use App\Filament\Resources\PortalEpisodes\PortalEpisodeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPortalEpisode extends EditRecord
{
    protected static string $resource = PortalEpisodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
