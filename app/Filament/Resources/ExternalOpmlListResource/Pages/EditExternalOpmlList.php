<?php

namespace App\Filament\Resources\ExternalOpmlListResource\Pages;

use App\Filament\Resources\ExternalOpmlListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalOpmlList extends EditRecord
{
    protected static string $resource = ExternalOpmlListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
