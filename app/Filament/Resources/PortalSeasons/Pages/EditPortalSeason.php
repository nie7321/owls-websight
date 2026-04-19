<?php

namespace App\Filament\Resources\PortalSeasons\Pages;

use App\Filament\Resources\PortalSeasons\PortalSeasonResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPortalSeason extends EditRecord
{
    protected static string $resource = PortalSeasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
