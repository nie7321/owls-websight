<?php

namespace App\Filament\Resources;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use App\Domains\Media\Actions\Exif;
use App\Filament\Resources\GalleryResource\Pages;
use App\Filament\Resources\GalleryResource\RelationManagers;
use App\Domains\Media\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
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

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(4000)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?Gallery $gallery, ?string $state) {
                        // Only update it automatically for unsaved stuff, to avoid breaking markdown refs.
                        if ($gallery->exists) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(4000)
                    ->unique(ignoreRecord: true),
                Forms\Components\MarkdownEditor::make('content')
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('attachments')
                    ->saveUploadedFileAttachmentsUsing(function (TemporaryUploadedFile $file, Forms\Components\Component $component, Exif $exifTool) {
                        $exifTool->stripMetadata($file->path());
                        return \Livewire\invade($component)->handleFileAttachmentUpload($file);
                    }),
                Forms\Components\Select::make('author_user_id')
                    ->label('Author')
                    ->required()
                    ->options(User::pluck('name', 'id'))
                    ->default(fn () => auth()->user()->id),
                Forms\Components\DateTimePicker::make('published_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('status')
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->placeholder('unpublished')
                    ->dateTime()
                    ->sortable(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
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
