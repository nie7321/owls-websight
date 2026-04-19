<?php

namespace App\Filament\Resources\PortalEpisodes\Schemas;

use App\Domains\Foundation\Filament\Forms\Components\RelationshipTagInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PortalEpisodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('portal_season_id')
                    ->required()
                    ->relationship('season', 'name'),
                TextInput::make('episode_number')
                    ->required()
                    ->numeric()
                    ->scopedUnique(modifyQueryUsing: function (Builder $query, Get $get) {
                        $seasonId = $get('portal_season_id');
                        if (! $seasonId) {
                            return $query;
                        }

                        return $query->where('portal_season_id', $get('portal_season_id'));
                    }),
                TextInput::make('name')
                    ->label('Episode Name')
                    ->required()
                    ->columnSpanFull(),
                MarkdownEditor::make('g4_description')
                    ->required()
                    ->columnSpanFull()
                    ->hint(Str::of('From <a href="https://web.archive.org/web/20041010232211/http://www.g4techtv.com:80/portal/episodes/index.html">the Internet Archive</a>')->toHtmlString()),
                MarkdownEditor::make('short_description')
                    ->required()
                    ->columnSpanFull(),
                MarkdownEditor::make('full_description')
                    ->required()
                    ->columnSpanFull(),
                RelationshipTagInput::make('tags')
                    ->helperText('List all games mentioned in the episode')
                    ->columnSpanFull(),
            ]);
    }
}
