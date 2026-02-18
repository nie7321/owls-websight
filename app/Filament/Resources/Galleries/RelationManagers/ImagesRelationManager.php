<?php

namespace App\Filament\Resources\Galleries\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images_filament';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function getTableReorderColumn(): ?string
    {
        return 'order_index';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable($this->getTableReorderColumn())
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->conversion('preview'),
                TextColumn::make('name'),
                TextColumn::make('caption'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('created_at', 'desc'))
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('order_index')
                            ->numeric()
                            ->required()
                            ->default(fn () => $this->ownerRecord->images()->count() + 1),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema(fn (EditAction $action): array => [
                        TextInput::make('order_index')->numeric()->required(),
                    ]),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('order_index');
    }
}
