<?php

use App\Models\Course;
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
            $table->dropColumn('test_score');
            $table->dropForeignIdFor(Course::class);
            $table->dropColumn('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignIdFor(Course::class)->nullable()->default(null)->constrained();
            $table->integer('test_score')->nullable()->default(null);
        });
    }
};
