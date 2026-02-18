<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\ExternalOpmlListResource\Pages\ListExternalOpmlLists;
use App\Filament\Resources\ExternalOpmlListResource\Pages\CreateExternalOpmlList;
use App\Filament\Resources\ExternalOpmlListResource\Pages\EditExternalOpmlList;
use App\Filament\Resources\ExternalOpmlListResource\Pages;
use App\Filament\Resources\ExternalOpmlListResource\RelationManagers;
use App\Domains\Opml\Models\ExternalOpmlList;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExternalOpmlListResource extends Resource
{
    protected static ?string $label = 'OPML Canonicalizer';

    protected static ?string $pluralLabel = 'OPML Canonicalizer';

    protected static string | \UnitEnum | null $navigationGroup = 'Tools';

    protected static ?string $model = ExternalOpmlList::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->maxLength(255),
                TextInput::make('output_filename')
                    ->required()
                    ->maxLength(255),
                TextInput::make('docs_url')
                    ->label('Docs URL')
                    ->maxLength(255)
                    ->helperText('Added to the <head>, should explain the modifications to the feed'),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('label')
                    ->searchable(),
                TextColumn::make('republished_url')
                    ->label('Republished URL')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('docs_url')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => ListExternalOpmlLists::route('/'),
            'create' => CreateExternalOpmlList::route('/create'),
            'edit' => EditExternalOpmlList::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
