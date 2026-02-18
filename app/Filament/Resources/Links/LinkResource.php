<?php

namespace App\Filament\Resources\Links;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\Links\Pages\ListLinks;
use App\Filament\Resources\Links\Pages\CreateLink;
use App\Filament\Resources\Links\Pages\EditLink;
use App\Domains\OpenGraph\Actions\CacheOpenGraphImage;
use App\Domains\OpenGraph\Actions\FetchOpenGraph;
use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers;
use App\Domains\Blog\Models\Link;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Blog';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-link';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('link_category_id')
                    ->label('Category')
                    ->relationship(name: 'category', titleAttribute: 'label')
                    ->required(),
                TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->maxLength(4000),
                Select::make('relationships')
                    ->label('XFN Relationships')
                    ->multiple()
                    ->preload()
                    ->relationship('relationships', 'label')
                    ->columnSpanFull(),
                Toggle::make('auto_update_card')
                    ->label('Enable Automatic Card Updates?')
                    ->live()
                    ->afterStateUpdated(function (
                        Toggle $component,
                        Set $set,
                        Get $get,
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

                        /** @var DateTimePicker $polledAtComponent */
                        $polledAtComponent = $component->getContainer()->getComponent('card_last_polled_at');

                        $set('title', $metadata->siteName ?? $metadata->title);
                        $set('description', $metadata->description);
                        $set('card_image_path', $cardImage);
                        $set($polledAtComponent->getName(), Carbon::now()->format($polledAtComponent->getFormat()));
                    }),
                DateTimePicker::make('card_last_polled_at')
                    ->key('card_last_polled_at'),
                Fieldset::make('card_details')
                    ->key('card_details')
                    ->label('Card Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(4000)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('card_image_path')
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
                ImageColumn::make('card_image_path')
                    ->label('Preview')
                    ->disk('public')
                    ->defaultImageUrl(fn (Link $link) => $link->card_image_asset_path),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable(),
                TextColumn::make('category.label')
                    ->label('Category')
                    ->searchable(),
                IconColumn::make('auto_update_card')
                    ->label('Auto-update?')
                    ->boolean(),
                TextColumn::make('card_last_polled_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => ListLinks::route('/'),
            'create' => CreateLink::route('/create'),
            'edit' => EditLink::route('/{record}/edit'),
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
