<?php

namespace App\Filament\Resources\Bots\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'post_history';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identifier')
            ->columns([
                TextColumn::make('identifier'),
                TextColumn::make('created_at'),
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('created_at', 'DESC')->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
