<?php

namespace App\Filament\Resources\Galleries;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\Galleries\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\Galleries\Pages\ListGalleries;
use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use function Livewire\invade;
use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use App\Domains\Media\Actions\Exif;
use App\Domains\Media\Models\Gallery;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Blog';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-folder';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(4000)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?Gallery $gallery, ?string $state) {
                        // Only update it automatically for unsaved stuff, to avoid breaking markdown refs.
                        if ($gallery?->exists) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(4000)
                    ->unique(ignoreRecord: true),
                MarkdownEditor::make('content')
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('attachments')
                    ->saveUploadedFileAttachmentUsing(function (TemporaryUploadedFile $file, Component $component, Exif $exifTool) {
                        $exifTool->stripMetadata($file->path());

                        /** @var MarkdownEditor $component */
                        return $file->storePublicly($component->getFileAttachmentsDirectory(), $component->getFileAttachmentsDiskName());
                    }),
                Select::make('author_user_id')
                    ->label('Author')
                    ->required()
                    ->options(User::pluck('name', 'id'))
                    ->default(fn () => auth()->user()->id),
                DateTimePicker::make('published_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('status')
                    ->icon(fn (PublishingStatus $state) => match ($state) {
                        PublishingStatus::DRAFT => 'heroicon-o-pencil',
                        PublishingStatus::SCHEDULED => 'heroicon-o-clock',
                        PublishingStatus::PUBLISHED => 'heroicon-o-check-circle',
                    })
                    ->color(fn (PublishingStatus $state) => match ($state) {
                        PublishingStatus::DRAFT => 'info',
                        PublishingStatus::SCHEDULED => 'warning',
                        PublishingStatus::PUBLISHED => 'success',
                    }),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->placeholder('unpublished')
                    ->dateTime()
                    ->sortable(),
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleries::route('/'),
            'create' => CreateGallery::route('/create'),
            'edit' => EditGallery::route('/{record}/edit'),
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
