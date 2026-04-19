<?php

namespace App\Filament\Resources\PortalEpisodes;

use App\Domains\Portal\Models\PortalEpisode;
use App\Filament\Resources\PortalEpisodes\Pages\CreatePortalEpisode;
use App\Filament\Resources\PortalEpisodes\Pages\EditPortalEpisode;
use App\Filament\Resources\PortalEpisodes\Pages\ListPortalEpisodes;
use App\Filament\Resources\PortalEpisodes\RelationManagers\CharactersRelationManager;
use App\Filament\Resources\PortalEpisodes\Schemas\PortalEpisodeForm;
use App\Filament\Resources\PortalEpisodes\Tables\PortalEpisodesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PortalEpisodeResource extends Resource
{
    protected static ?string $model = PortalEpisode::class;

    protected static ?string $navigationLabel = 'Episodes';

    protected static string | \UnitEnum | null $navigationGroup = 'Portal';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTv;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PortalEpisodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortalEpisodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CharactersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPortalEpisodes::route('/'),
            'create' => CreatePortalEpisode::route('/create'),
            'edit' => EditPortalEpisode::route('/{record}/edit'),
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
