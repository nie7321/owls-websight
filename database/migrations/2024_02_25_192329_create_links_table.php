<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_categories', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unqiue();
            $table->string('label');
            $table->text('description');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('link_category_id')->index();
            $table->string('url', 4000);
            $table->string('title', 4000);
            $table->text('description');

            $table->boolean('auto_update_card');
            $table->string('card_image_path')->nullable();
            $table->dateTime('card_last_polled_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('relationship_types', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unqiue();
            $table->string('label');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('link_relationship_type', function (Blueprint $table) {
            $table->id();

            $table->foreignId('link_id')->index();
            $table->foreignId('relationship_type_id')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        throw new NoRollback;
    }
};
