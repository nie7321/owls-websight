<?php

use App\Domains\Foundation\Exceptions\NoRollback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_backends', function (Blueprint $table) {
            $table->id();

            $table->string('type')->unique();
            $table->string('label');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bots', function (Blueprint $table) {
            $table->id();

            $table->string('username');
            $table->string('server_url');
            $table->string('access_token', 4000);
            $table->foreignId('bot_backend_id')->index();
            $table->json('configuration');

            $table->string('check_frequency_interval');
            $table->datetime('next_check_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bot_post_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bot_id')->index();
            $table->string('identifier');
            $table->text('content');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        throw new NoRollback;
    }
};
