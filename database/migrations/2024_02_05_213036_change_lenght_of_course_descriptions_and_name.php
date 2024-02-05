<?php

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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('name', 64)->change();
            $table->string('description_en', 512)->change();
            $table->string('description_ru', 512)->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('name', 300)->change();
            $table->string('description_en', 255)->change();
            $table->string('description_ru', 255)->change();
        });
    }
};
