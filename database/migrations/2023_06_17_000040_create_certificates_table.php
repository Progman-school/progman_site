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
        Schema::create('requests', function (Blueprint $table) {
            $table->id()->autoIncrement()->primary();
            $table->string('full_number', 30)->unique()->nullable()->default(null);
            $table->integer('user')->nullable(false);
            $table->integer('course')->nullable(false);
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('date')->useCurrentOnUpdate();
            $table->integer('hours')->nullable(false);
            $table->string('description', 5000)->nullable(false);
            $table->enum("language", ["ru", "en"]);
            $table->enum("blank", ["ru", "en"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
