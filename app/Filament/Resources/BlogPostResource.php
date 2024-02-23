<?php

namespace App\Filament\Resources;

use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use App\Domains\Foundation\Filament\Actions\NowAction;
use App\Domains\Foundation\Filament\Forms\Components\RelationshipTagInput;
use App\Domains\Media\Actions\Exif;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Domains\Blog\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $modelLabel = 'Posts';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(4000)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?BlogPost $post, ?string $state) {
                        // Only update it automatically for drafts.
                        // Otherwise the permalink will break and that's bad.
                        if ($post->exists && $post->status !== PublishingStatus::DRAFT) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(4000)
                    ->unique(ignoreRecord: true),
                Forms\Components\MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('attachments')
                    ->saveUploadedFileAttachmentsUsing(function (TemporaryUploadedFile $file, Forms\Components\Component $component, Exif $exifTool) {
                        $exifTool->stripMetadata($file->path());
                        return \Livewire\invade($component)->handleFileAttachmentUpload($file);
                    }),
                Forms\Components\MarkdownEditor::make('summary')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('thumbnail_image_id')
                    ->label('Thumbnail Image')
                    ->relationship(
                        name: 'thumbnail_image',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('created_at', 'desc'),
                    )
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('author_user_id')
                    ->label('Author')
                    ->required()
                    ->options(User::pluck('name', 'id'))
                    ->default(fn () => auth()->user()->id),
                RelationshipTagInput::make('tags'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->suffixAction(NowAction::make('published_at_now')),
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
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
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
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
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
