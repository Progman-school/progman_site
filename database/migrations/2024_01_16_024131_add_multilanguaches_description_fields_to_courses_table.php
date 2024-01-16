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
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->string('description_' . TagService::EN_LANGUAGE)->nullable()->after("name");
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->string('description_' . TagService::RU_LANGUAGE)->nullable()->after('description_' . TagService::EN_LANGUAGE);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('description_' . TagService::EN_LANGUAGE);
            $table->dropColumn('description_' . TagService::RU_LANGUAGE);
            $table->string('description')->nullable()->after("name");
        });
    }
};
