<?php

namespace App\Filament\Resources\ExternalOpmlLists\Pages;

use App\Filament\Resources\ExternalOpmlLists\ExternalOpmlListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExternalOpmlList extends CreateRecord
{
    protected static string $resource = ExternalOpmlListResource::class;
}
