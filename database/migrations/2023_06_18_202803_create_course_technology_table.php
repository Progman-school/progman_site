<?php

use App\Models\Course;
use App\Models\Technology;
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
        Schema::create('course_technology', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->constrained();
            $table->foreignIdFor(Technology::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_technology');
    }
};
