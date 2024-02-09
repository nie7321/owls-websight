<?php

namespace App\Filament\Resources\BotResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'post_history';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identifier')
            ->columns([
                Tables\Columns\TextColumn::make('identifier'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('created_at', 'DESC')->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
