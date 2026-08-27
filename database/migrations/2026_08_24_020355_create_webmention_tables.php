<?php

use App\Domains\Foundation\Exceptions\NoRollback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('known_domains', function(Blueprint $table) {
            $table->id();

            $table->string('domain')->unique();

            $table->boolean('supports_webmentions')->nullable();
            $table->dateTime('webmention_support_last_checked_at')->nullable();

            $table->boolean('outbound_webmentions_enabled')->default(true);
            $table->boolean('inbound_webmentions_enabled')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blog_post_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->index();

            $table->foreignId('known_domain_id')->index();
            $table->string('url');

            $table->string('mf2_type')->nullable();

            $table->integer('webmention_attempts')->default(0);
            $table->dateTime('webmention_last_attempt_at')->nullable();
            $table->dateTime('webmention_success_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blog_post_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->index();

            $table->foreignId('known_domain_id')->index();
            $table->string('url');

            $table->integer('verification_attempts')->default(0);
            $table->dateTime('verification_last_attempted_at')->nullable();
            $table->dateTime('verification_success_at')->nullable();

            $table->string('mf2_type')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        throw new NoRollback();
    }
};
