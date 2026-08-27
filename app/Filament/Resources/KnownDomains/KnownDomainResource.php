<?php

namespace App\Filament\Resources\KnownDomains;

use App\Domains\Webmention\Models\KnownDomain;
use App\Filament\Resources\KnownDomains\Pages\CreateKnownDomain;
use App\Filament\Resources\KnownDomains\Pages\EditKnownDomain;
use App\Filament\Resources\KnownDomains\Pages\ListKnownDomains;
use App\Filament\Resources\KnownDomains\Schemas\KnownDomainForm;
use App\Filament\Resources\KnownDomains\Tables\KnownDomainsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KnownDomainResource extends Resource
{
    protected static ?string $model = KnownDomain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Moon;

    protected static ?string $recordTitleAttribute = 'domain';

    protected static string | \UnitEnum | null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 80;

    public static function form(Schema $schema): Schema
    {
        return KnownDomainForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KnownDomainsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKnownDomains::route('/'),
            'create' => CreateKnownDomain::route('/create'),
            'edit' => EditKnownDomain::route('/{record}/edit'),
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
