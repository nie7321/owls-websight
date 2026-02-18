<?php

namespace App\Filament\Resources\Images;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\Images\Pages\ListImages;
use App\Filament\Resources\Images\Pages\CreateImage;
use App\Filament\Resources\Images\Pages\EditImage;
use App\Filament\Resources\Images\Pages\BulkImageUpload;
use App\Domains\Foundation\Filament\Actions\CopyToClipboardAction;
use App\Domains\Media\Actions\Exif;
use App\Filament\Resources\ImageResource\Pages;
use App\Filament\Resources\ImageResource\RelationManagers;
use App\Domains\Media\Models\Image;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Blog';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    public static function form(Schema $schema): Schema
    {
        $uploadComponent = SpatieMediaLibraryFileUpload::make('media');

        /** @var callable(SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string $originalCallback */
        $originalCallback = invade($uploadComponent)->saveUploadedFileUsing;
        $replacementCallback = function (SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record, Exif $exifTool) use ($originalCallback) {
            $exifTool->stripMetadata($file->path());
            return $originalCallback($component, $file, $record);
        };

        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Internal Name')
                    ->required()
                    ->maxLength(2000),
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(2000),
                Textarea::make('alt_description'),
                Textarea::make('caption'),
                $uploadComponent
                    ->required()
                    ->columnSpanFull()
                    ->saveUploadedFileUsing($replacementCallback),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->conversion('preview'),
                TextColumn::make('name')
                    ->label('Internal Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('caption')
                    ->sortable()
                    ->searchable()
                    ->placeholder('<none>'),
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
                CopyToClipboardAction::make()
                    ->label('Copy URL')
                    ->copyable(fn (Image $image) => $image->getFirstMedia()->getUrl()),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(25)
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListImages::route('/'),
            'create' => CreateImage::route('/create'),
            'edit' => EditImage::route('/{record}/edit'),
            'bulk-create' => BulkImageUpload::route('/bulk-create'),
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
