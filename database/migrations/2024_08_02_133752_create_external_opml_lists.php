<?php

use App\Domains\Foundation\Exceptions\NoRollback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('external_opml_lists', function (Blueprint $table) {
            $table->id();

            $table->string('label');
            $table->string('url');
            $table->string('output_filename');
            $table->string('docs_url')->nullable();
            $table->boolean('active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        throw new NoRollback();
    }
};
