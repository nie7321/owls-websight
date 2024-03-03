<?php

use App\Domains\Foundation\Exceptions\NoRollback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('link_categories', function (Blueprint $table) {
            $table->tinyInteger('order_index')->default(5);
        });
    }

    public function down(): void
    {
        throw new NoRollback();
    }
};
