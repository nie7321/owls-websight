<?php

namespace App\Filament\Resources\PortalSeasons;

use App\Domains\Portal\Models\PortalSeason;
use App\Filament\Resources\PortalSeasons\Pages\CreatePortalSeason;
use App\Filament\Resources\PortalSeasons\Pages\EditPortalSeason;
use App\Filament\Resources\PortalSeasons\Pages\ListPortalSeasons;
use App\Filament\Resources\PortalSeasons\RelationManagers\EpisodesRelationManager;
use App\Filament\Resources\PortalSeasons\Schemas\PortalSeasonForm;
use App\Filament\Resources\PortalSeasons\Tables\PortalSeasonsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PortalSeasonResource extends Resource
{
    protected static ?string $model = PortalSeason::class;

    protected static ?string $navigationLabel = 'Seasons';

    protected static string | \UnitEnum | null $navigationGroup = 'Portal';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PortalSeasonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PortalSeasonsTable::configure($table);
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
            'index' => ListPortalSeasons::route('/'),
            'create' => CreatePortalSeason::route('/create'),
            'edit' => EditPortalSeason::route('/{record}/edit'),
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
