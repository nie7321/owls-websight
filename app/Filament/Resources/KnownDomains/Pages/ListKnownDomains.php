<?php

namespace App\Filament\Resources\KnownDomains\Pages;

use App\Filament\Resources\KnownDomains\KnownDomainResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKnownDomains extends ListRecords
{
    protected static string $resource = KnownDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
