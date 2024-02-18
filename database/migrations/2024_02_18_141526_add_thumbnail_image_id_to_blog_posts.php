<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreignId('thumbnail_image_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        throw new NoRollback;
    }
};
