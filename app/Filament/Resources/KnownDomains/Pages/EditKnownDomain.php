<?php

namespace App\Filament\Resources\KnownDomains\Pages;

use App\Filament\Resources\KnownDomains\KnownDomainResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditKnownDomain extends EditRecord
{
    protected static string $resource = KnownDomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
