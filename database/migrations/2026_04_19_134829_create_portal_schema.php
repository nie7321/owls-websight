<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_seasons', function (Blueprint $table) {
            $table->id();

            $table->tinyInteger('season_number')->index();
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('portal_episodes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('portal_season_id')->index();
            $table->tinyInteger('episode_number')->index();

            $table->string('name');
            $table->date('air_date');
            $table->text('watch_url');
            $table->text('g4_description');
            $table->text('short_description');
            $table->text('full_description');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('portal_episode_tag', function (Blueprint $table) {
            $table->id();

            $table->foreignId('portal_episode_id')->index();
            $table->foreignId('tag_id')->index();

            $table->timestamps();
        });

        Schema::create('portal_characters', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('name');

            $table->text('short_description');
            $table->text('description');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('portal_character_portal_episode', function (Blueprint $table) {
            $table->id();

            $table->foreignId('portal_character_id')->index();
            $table->foreignId('portal_episode_id')->index();

            $table->text('role_in_episode');
            $table->tinyInteger('order_index')->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        throw new NoRollback();
    }
};
