<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Closure;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\BotResource\RelationManagers\PostHistoryRelationManager;
use App\Filament\Resources\BotResource\Pages\ListBots;
use App\Filament\Resources\BotResource\Pages\CreateBot;
use App\Filament\Resources\BotResource\Pages\EditBot;
use App\Domains\FediBot\Adapters\BotFactory;
use App\Domains\FediBot\Models\BotBackend;
use App\Filament\Resources\BotResource\Pages;
use App\Filament\Resources\BotResource\RelationManagers;
use App\Domains\FediBot\Models\Bot;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BotResource extends Resource
{
    protected static ?string $label = 'Fedi Bots';

    protected static string | \UnitEnum | null $navigationGroup = 'Tools';

    protected static ?string $model = Bot::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->required()
                    ->maxLength(2000),
                TextInput::make('access_token')
                    ->required()
                    ->password()
                    ->maxLength(4000),
                TextInput::make('server_url')
                    ->label('Server URL')
                    ->required()
                    ->url()
                    ->maxLength(2000),
                Select::make('bot_backend_id')
                    ->label('Backend')
                    ->required()
                    ->options(BotBackend::orderBy('label')->pluck('label', 'id'))
                    ->reactive(),
                Textarea::make('configuration')
                    ->required()
                    ->json()
                    ->rule(function (callable $get, BotFactory $factory): callable {
                        $backendId = $get('bot_backend_id');
                        if (! $backendId) {
                            return fn () => null;
                        }

                        $backend = $factory->toBackend(BotBackend::findOrFail($backendId)->type);
                        return function (string $attribute, mixed $value, Closure $fail) use ($backend) {
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
                    ->afterStateHydrated(fn (Textarea $component, $state) => $component->state(json_encode($state, JSON_PRETTY_PRINT)))
                    ->dehydrateStateUsing(fn (?string $state) => json_decode($state) ?? '{}'),
                TextInput::make('check_frequency_interval')
                    ->label('Check Frequency Interval')
                    ->helperText('Any CarbonInterval-compatible string')
                    ->required()
                    ->maxLength(2000),
                DateTimePicker::make('next_check_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('server_url')
                    ->label('Server URL')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('backend.label')
                    ->sortable(),
                TextColumn::make('check_frequency_interval')
                    ->label('Check Frequency')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('next_check_at')
                    ->label('Next Check At')
                    ->placeholder('not scheduled')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('post_history_count')
                    ->label('Post Count')
                    ->sortable()
                    ->counts('post_history'),
                TextColumn::make('latest_post.created_at')
                    ->label('Latest Post At')
                    ->placeholder('none')
                    ->sortable()
                    ->dateTime(),
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
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
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
            PostHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBots::route('/'),
            'create' => CreateBot::route('/create'),
            'edit' => EditBot::route('/{record}/edit'),
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
