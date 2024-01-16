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
            $table->renameColumn('description', 'description_' . TagService::EN_LANGUAGE);
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
            $table->renameColumn('description_' . TagService::EN_LANGUAGE, 'description');
            $table->dropColumn('description_' . TagService::RU_LANGUAGE);
        });
    }
};
