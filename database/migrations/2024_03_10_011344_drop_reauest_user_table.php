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
        Schema::drop('request_user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('request_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Request::class)->constrained();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
        });
    }
};
