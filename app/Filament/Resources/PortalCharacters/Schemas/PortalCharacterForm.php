<?php

namespace App\Filament\Resources\PortalCharacters\Schemas;

use App\Domains\Blog\Enums\PublishingStatus;
use App\Domains\Blog\Models\BlogPost;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PortalCharacterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Character Name')
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
                    ->unique(ignoreRecord: true),
                MarkdownEditor::make('short_description')
                    ->required()
                    ->columnSpanFull(),
                MarkdownEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
