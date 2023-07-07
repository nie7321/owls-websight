<?php

namespace App\Domains\Foundation\Seeders;

/**
 * You can implement this interface to mark a seeder as idempotent and allow {@see DatabaseSeeder} to run it.
 */
interface IdempotentSeederInterface
{
    public function run(): void;
}
