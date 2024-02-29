<?php

namespace App\Filament\Resources;

use App\Domains\OpenGraph\Actions\CacheOpenGraphImage;
use App\Domains\OpenGraph\Actions\FetchOpenGraph;
use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers;
use App\Domains\Blog\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('link_category_id')
                    ->label('Category')
                    ->relationship(name: 'category', titleAttribute: 'label')
                    ->required(),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->maxLength(4000),
                Forms\Components\Select::make('relationships')
                    ->label('XFN Relationships')
                    ->multiple()
                    ->preload()
                    ->relationship('relationships', 'label')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('auto_update_card')
                    ->label('Enable Automatic Card Updates?')
                    ->live()
                    ->afterStateUpdated(function (
                        Forms\Components\Toggle $component,
                        Forms\Set $set,
                        Forms\Get $get,
                        FetchOpenGraph $openGraph,
                        CacheOpenGraphImage $ogImageCacher,
                        bool $state
                    ) {
                        $fieldSet = $component->getContainer()->getComponent('card_details');

                        $url = $get('url');
                        if (! $state || ! $url) {
                            return;
                        }

                        $metadata = $openGraph->fetch($url);
                        if (! $metadata) {
                            return;
                        }

                        $cardImage = [];
                        if ($metadata->imageUrl) {
                            $cardImage = [$ogImageCacher($metadata->imageUrl)];
                        }

                        /** @var Forms\Components\DateTimePicker $polledAtComponent */
                        $polledAtComponent = $component->getContainer()->getComponent('card_last_polled_at');

                        $set('title', $metadata->siteName ?? $metadata->title);
                        $set('description', $metadata->description);
                        $set('card_image_path', $cardImage);
                        $set($polledAtComponent->getName(), Carbon::now()->format($polledAtComponent->getFormat()));
                    }),
                Forms\Components\DateTimePicker::make('card_last_polled_at')
                    ->key('card_last_polled_at'),
                Forms\Components\Fieldset::make('card_details')
                    ->key('card_details')
                    ->label('Card Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(4000)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('card_image_path')
                            ->disk('public')
                            ->label('Card Image')
                            ->image()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('card_image_path')
                    ->label('Preview')
                    ->disk('public')
                    ->defaultImageUrl(fn (Link $link) => $link->card_image_asset_path),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.label')
                    ->label('Category')
                    ->searchable(),
                Tables\Columns\IconColumn::make('auto_update_card')
                    ->label('Auto-update?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('card_last_polled_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
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
