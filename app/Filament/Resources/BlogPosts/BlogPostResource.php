<?php

namespace App\Filament\Resources\BlogPosts;

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
use App\Filament\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Filament\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPosts\Pages\EditBlogPost;
use function Livewire\invade;
use App\Domains\Auth\Models\User;
use App\Domains\Blog\Enums\PublishingStatus;
use App\Domains\Foundation\Filament\Actions\NowAction;
use App\Domains\Foundation\Filament\Forms\Components\RelationshipTagInput;
use App\Domains\Media\Actions\Exif;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers;
use App\Domains\Blog\Models\BlogPost;
use Filament\Forms;
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

    protected static string | \UnitEnum | null $navigationGroup = 'Blog';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-pencil-square';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(4000)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?BlogPost $post, ?string $state) {
                        // Only update it automatically for drafts.
                        // Otherwise the permalink will break and that's bad.
                        if ($post?->exists && $post->status !== PublishingStatus::DRAFT) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(4000)
                    ->unique(ignoreRecord: true),
                MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('attachments')
                    ->saveUploadedFileAttachmentUsing(function (TemporaryUploadedFile $file, Component $component, Exif $exifTool) {
                        $exifTool->stripMetadata($file->path());

                        /** @var MarkdownEditor $component */
                        return $file->storePublicly($component->getFileAttachmentsDirectory(), $component->getFileAttachmentsDiskName());
                    }),
                MarkdownEditor::make('summary')
                    ->required()
                    ->columnSpanFull(),
                Select::make('thumbnail_image_id')
                    ->label('Thumbnail Image')
                    ->relationship(
                        name: 'thumbnail_image',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('created_at', 'desc'),
                    )
                    ->searchable()
                    ->preload(),
                Select::make('author_user_id')
                    ->label('Author')
                    ->required()
                    ->options(User::pluck('name', 'id'))
                    ->default(fn () => auth()->user()->id),
                RelationshipTagInput::make('tags'),
                DateTimePicker::make('published_at')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->suffixAction(NowAction::make('published_at_now')),
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
                TextColumn::make('author.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('published_at')
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
            ->defaultSort('created_at', 'desc')
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
            'index' => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit' => EditBlogPost::route('/{record}/edit'),
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
