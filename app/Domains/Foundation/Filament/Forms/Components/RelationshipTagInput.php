<?php

namespace App\Domains\Foundation\Filament\Forms\Components;

use App\Domains\Blog\Models\Tag;
use Filament\Forms\Components\TagsInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Based on spatie-laravel-tags-plugin.
 *
 * @see https://github.com/filamentphp/spatie-laravel-tags-plugin/blob/3.x/src/Forms/Components/SpatieTagsInput.php
 */
class RelationshipTagInput extends TagsInput
{
    protected function setUp(): void
    {
        $this->default([]);

        $this->loadStateFromRelationshipsUsing(static function (self $component, ?Model $record): void {
            if (! $record) {
                return;
            }

            $record->loadMissing('tags');
            $tags = $record->getRelationValue('tags');

            $component->state($tags->pluck('label')->all());
        });

        $this->saveRelationshipsUsing(static function (self $component, ?Model $record, array $state) {
            if (! $record) {
                return;
            }

            $tagModels = collect($state)
                ->map(fn (string $label) => collect(['label' => $label, 'slug' => Str::slug($label)]))
                ->map(fn (Collection $data) => Tag::createOrFirst($data->only('slug')->all(), $data->only('label')->all()));

            $record->tags()->sync($tagModels->map->id->all());
        });

        $this->dehydrated(false);
    }

    public function getSuggestions(): array
    {
        return Tag::pluck('label')->all();
    }
}
