<?php

use App\Domains\Foundation\Exceptions\NoRollback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();

            $table->string('title', 4000);
            $table->string('slug', 4000)->unique();
            $table->text('content');

            $table->foreignId('author_user_id')->index();

            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('label');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blog_post_id')->index();
            $table->foreignId('tag_id')->index();

            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();

            $table->string('title', 4000);
            $table->string('slug', 4000);
            $table->text('content');

            $table->foreignId('author_user_id')->index();

            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->json('metadata');
            $table->text('alt_description');
            $table->text('caption');

            $table->foreignId('media_id')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gallery_image', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gallery_id')->index();
            $table->foreignId('image_id')->index();

            $table->tinyInteger('order_index');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        throw new NoRollback;
    }
};
