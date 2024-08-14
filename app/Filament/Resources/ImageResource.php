<?php

namespace App\Filament\Resources;

use App\Domains\Foundation\Filament\Actions\CopyToClipboardAction;
use App\Domains\Media\Actions\Exif;
use App\Filament\Resources\ImageResource\Pages;
use App\Filament\Resources\ImageResource\RelationManagers;
use App\Domains\Media\Models\Image;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
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

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        $uploadComponent = Forms\Components\SpatieMediaLibraryFileUpload::make('media');

        /** @var callable(SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string $originalCallback */
        $originalCallback = invade($uploadComponent)->saveUploadedFileUsing;
        $replacementCallback = function (SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record, Exif $exifTool) use ($originalCallback) {
            $exifTool->stripMetadata($file->path());
            return $originalCallback($component, $file, $record);
        };

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Internal Name')
                    ->required()
                    ->maxLength(2000),
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(2000),
                Forms\Components\Textarea::make('alt_description'),
                Forms\Components\Textarea::make('caption'),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('media')
                    ->conversion('preview'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Internal Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('caption')
                    ->sortable()
                    ->searchable()
                    ->placeholder('<none>'),
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
                CopyToClipboardAction::make()
                    ->label('Copy URL')
                    ->copyable(fn (Image $image) => $image->getFirstMedia()->getUrl()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListImages::route('/'),
            'create' => Pages\CreateImage::route('/create'),
            'edit' => Pages\EditImage::route('/{record}/edit'),
            'bulk-create' => Pages\BulkImageUpload::route('/bulk-create'),
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
