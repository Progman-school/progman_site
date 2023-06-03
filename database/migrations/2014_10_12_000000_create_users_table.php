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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('tg_name', 255)->nullable();
            $table->string('tg_id', 30)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('user_name', 255)->nullable();
            $table->integer("test_score")->nullable();
            $table->tinyInteger("complete")->default(0);
            $table->string("reg_hash", 255)->unique()->nullable(false);
            $table->json("user_data");
            $table->json("test_data");
            $table->integer("repeater_count")->default(0);
            $table->string("real_last_name", 50)->nullable();
            $table->string("real_first_name", 50)->nullable();
            $table->string("real_middle_name", 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
