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
            $table->string('name', 50)->nullable(false);
            $table->enum("type", ['text','image','string']);
            $table->string('description', 300)->nullable();
            $table->integer("value")->nullable(false);
            $table->tinyInteger("show")->nullable(false)->default(1);
            $table->integer("order")->nullable(false)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
