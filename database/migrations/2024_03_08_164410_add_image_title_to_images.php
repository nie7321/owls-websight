<?php

use App\Domains\Foundation\Exceptions\NoRollback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('title')->nullable();
        });

        DB::update('UPDATE images SET title = name');

        Schema::table('images', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        throw new NoRollback();
    }
};
