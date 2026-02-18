<?php

namespace App\Filament\Resources\ExternalOpmlLists\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ExternalOpmlLists\ExternalOpmlListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalOpmlLists extends ListRecords
{
    protected static string $resource = ExternalOpmlListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
