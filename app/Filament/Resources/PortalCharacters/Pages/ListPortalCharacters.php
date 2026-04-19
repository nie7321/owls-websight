<?php

namespace App\Filament\Resources\PortalCharacters\Pages;

use App\Filament\Resources\PortalCharacters\PortalCharacterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPortalCharacters extends ListRecords
{
    protected static string $resource = PortalCharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
