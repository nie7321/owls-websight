<?php

namespace App\Filament\Resources\PortalCharacters;

use App\Domains\Portal\Models\PortalCharacter;
use App\Filament\Resources\PortalCharacters\Pages\CreatePortalCharacter;
use App\Filament\Resources\PortalCharacters\Pages\EditPortalCharacter;
use App\Filament\Resources\PortalCharacters\Pages\ListPortalCharacters;
use App\Filament\Resources\PortalCharacters\RelationManagers\EpisodesRelationManager;
use App\Filament\Resources\PortalCharacters\Schemas\PortalCharacterForm;
use App\Filament\Resources\PortalCharacters\Tables\PortalCharactersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PortalCharacterResource extends Resource
{
    protected static ?string $model = PortalCharacter::class;

    protected static ?string $navigationLabel = 'Characters';

    protected static string | \UnitEnum | null $navigationGroup = 'Portal';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PortalCharacterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortalCharactersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EpisodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPortalCharacters::route('/'),
            'create' => CreatePortalCharacter::route('/create'),
            'edit' => EditPortalCharacter::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
