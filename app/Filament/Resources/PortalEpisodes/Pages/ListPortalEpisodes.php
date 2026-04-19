<?php

namespace App\Filament\Resources\PortalEpisodes\Pages;

use App\Filament\Resources\PortalEpisodes\PortalEpisodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPortalEpisodes extends ListRecords
{
    protected static string $resource = PortalEpisodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
