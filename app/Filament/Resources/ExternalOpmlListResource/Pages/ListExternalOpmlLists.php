<?php

namespace App\Filament\Resources\ExternalOpmlListResource\Pages;

use App\Filament\Resources\ExternalOpmlListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalOpmlLists extends ListRecords
{
    protected static string $resource = ExternalOpmlListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
