<?php

namespace App\Filament\Resources\PortalCharacters\Pages;

use App\Filament\Resources\PortalCharacters\PortalCharacterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPortalCharacter extends EditRecord
{
    protected static string $resource = PortalCharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
