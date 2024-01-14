<?php

use App\Services\TagService;
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
            $table->enum('language', [
                TagService::EN_LANGUAGE,
                TagService::RU_LANGUAGE
            ])->nullable(false)->default(TagService::EN_LANGUAGE);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
};
