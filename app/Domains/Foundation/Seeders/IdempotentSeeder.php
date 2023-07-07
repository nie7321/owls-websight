<?php

namespace App\Domains\Foundation\Seeders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Seeder;

/**
 * An idempotent seeder.
 *
 * It will look for rows already existing in the table by one column. Rows that are found get updated, rows that are not
 * found are inserted, and rows that exist in the DB but are no longer listed in {@see IdempotentSeeder::data()} are
 * deleted.
 *
 * Your lookup table should use the {@see SoftDeletes} trait, or else the deletion part may cause some unpleasant
 * problems.
 */
abstract class IdempotentSeeder extends Seeder implements IdempotentSeederInterface
{
    /**
     * Model to use for select/update/insert/delete operations.
     *
     * @return class-string<Model>
     */
    protected string $model;

    /**
     * Column name that should be used to find the existing row.
     */
    protected string $slugColumn;

    public function run(): void
    {
        $touchedSlugs = [];

        foreach ($this->data() as $row) {
            $row = collect($row);

            /** @var Builder $builder */
            $builder = $this->model::query();

            // For models using SoftDelete, un-deleting requires some special handling: the query needs to include
            // soft-deleted models, and the update needs to reset the deleted_at key.
            if (method_exists($builder, 'withTrashed')) {
                $builder = $builder->withTrashed();
                $row->put((new $this->model)->getDeletedAtColumn(), null);
            }

            $builder->updateOrCreate(
                $row->only($this->slugColumn)->all(),
                $row->except($this->slugColumn)->all(),
            );

            $touchedSlugs[] = $row->get($this->slugColumn);
        }

        $this->model::whereNotIn($this->slugColumn, $touchedSlugs)->get()->each->delete();
    }

    /**
     * @return array<array<string, mixed>>
     */
    abstract public function data(): array;
}
