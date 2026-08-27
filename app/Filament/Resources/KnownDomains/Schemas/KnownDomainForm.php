<?php

namespace App\Filament\Resources\KnownDomains\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class KnownDomainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('domain')
                    ->required(),
                DateTimePicker::make('webmention_support_last_checked_at'),
                ToggleButtons::make('supports_webmentions')
                    ->boolean()
                    ->grouped(),
                ToggleButtons::make('outbound_webmentions_enabled')
                    ->boolean()
                    ->grouped()
                    ->required(),
                ToggleButtons::make('inbound_webmentions_enabled')
                    ->boolean()
                    ->grouped()
                    ->required(),
            ]);
    }
}
