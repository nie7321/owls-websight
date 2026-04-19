<?php

namespace App\Filament\Resources\PortalSeasons\Pages;

use App\Filament\Resources\PortalSeasons\PortalSeasonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPortalSeasons extends ListRecords
{
    protected static string $resource = PortalSeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
