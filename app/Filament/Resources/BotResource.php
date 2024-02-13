<?php

namespace App\Filament\Resources;

use App\Domains\FediBot\Adapters\BotFactory;
use App\Domains\FediBot\Models\BotBackend;
use App\Filament\Resources\BotResource\Pages;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Domains\FediBot\Models\Bot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BotResource extends Resource
{
    protected static ?string $label = 'Fedi Bots';
    protected static ?string $model = Bot::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(2000),
                Forms\Components\TextInput::make('access_token')
                    ->required()
                    ->password()
                    ->maxLength(4000),
                Forms\Components\TextInput::make('server_url')
                    ->label('Server URL')
                    ->required()
                    ->url()
                    ->maxLength(2000),
                Forms\Components\Select::make('bot_backend_id')
                    ->label('Backend')
                    ->required()
                    ->options(BotBackend::orderBy('label')->pluck('label', 'id'))
                    ->reactive(),
                Forms\Components\Textarea::make('configuration')
                    ->required()
                    ->json()
                    ->rule(function (callable $get, BotFactory $factory): callable {
                        $backendId = $get('bot_backend_id');
                        if (! $backendId) {
                            return fn () => null;
                        }

                        $backend = $factory->toBackend(BotBackend::findOrFail($backendId)->type);
                        return function (string $attribute, mixed $value, \Closure $fail) use ($backend) {
                            if (is_string($value)) {
                                $value = json_decode($value, associative: true);
                            }

                            $value ??= [];
                            $errorBag = $backend->validateConfiguration($value);

                            foreach ($errorBag->getMessages() as $message) {
                                collect($message)
                                    ->each(fn (string $message) => $fail("{$attribute}: {$message}"));
                            }
                        };
                    })
                    ->rows(10)
                    ->cols(20)
                    ->columnSpanFull()
                    ->afterStateHydrated(fn (Forms\Components\Textarea $component, $state) => $component->state(json_encode($state, JSON_PRETTY_PRINT)))
                    ->dehydrateStateUsing(fn (?string $state) => json_decode($state) ?? '{}'),
                Forms\Components\TextInput::make('check_frequency_interval')
                    ->label('Check Frequency Interval')
                    ->helperText('Any CarbonInterval-compatible string')
                    ->required()
                    ->maxLength(2000),
                Forms\Components\DateTimePicker::make('next_check_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('server_url')
                    ->label('Server URL')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('backend.label')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_frequency_interval')
                    ->label('Check Frequency')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('next_check_at')
                    ->label('Next Check At')
                    ->placeholder('not scheduled')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('post_history_count')
                    ->label('Post Count')
                    ->sortable()
                    ->counts('post_history'),
                Tables\Columns\TextColumn::make('latest_post.created_at')
                    ->label('Latest Post At')
                    ->placeholder('none')
                    ->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PostHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBots::route('/'),
            'create' => Pages\CreateBot::route('/create'),
            'edit' => Pages\EditBot::route('/{record}/edit'),
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
