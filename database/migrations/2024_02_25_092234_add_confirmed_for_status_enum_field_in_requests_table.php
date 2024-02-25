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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn("status");
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->enum("status", [
                \App\Models\Request::RECEIVED_STATUS,
                \App\Models\Request::CONFIRMED_STATUS,
                \App\Models\Request::PROCESSED_STATUS,
            ])->nullable(false)->default(\App\Models\Request::RECEIVED_STATUS);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn("status");
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->enum("status", [
                \App\Models\Request::PROCESSED_STATUS,
                \App\Models\Request::RECEIVED_STATUS,
            ])->nullable(false)->default(\App\Models\Request::RECEIVED_STATUS);
        });
    }
};
