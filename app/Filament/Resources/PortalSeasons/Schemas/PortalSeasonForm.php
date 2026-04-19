<?php

namespace App\Filament\Resources\PortalSeasons\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PortalSeasonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('season_number')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
