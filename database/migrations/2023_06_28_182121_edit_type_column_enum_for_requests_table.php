<?php

use App\Models\Request;
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
        Schema::table(app(Request::class)->getTable(), function (Blueprint $table) {
            $table-> enum("type", ['email','telegram'])->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(app(Request::class)->getTable(), function (Blueprint $table) {
            $table->enum("type", ['registry', 'confirm'])->nullable(false)->change();
        });
    }
};
