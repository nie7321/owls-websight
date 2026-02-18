<?php

namespace App\Filament\Resources\ExternalOpmlLists\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Resources\ExternalOpmlLists\ExternalOpmlListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalOpmlList extends EditRecord
{
    protected static string $resource = ExternalOpmlListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
