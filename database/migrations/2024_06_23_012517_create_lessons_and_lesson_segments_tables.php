<?php

use App\Models\Certificate;
use App\Models\Coupon;
use App\Models\Purchase;
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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('admin_id')->nullable()->default(null)->constrained('admins');
            $table->foreignIdFor(Certificate::class);
            $table->string('comment')->nullable();
            $table->string('homework')->nullable();
            $table->dateTime('start_time');
        });

        Schema::create('lesson_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons');
            $table->foreignIdFor(Technology::class);
            $table->integer('duration');
            $table->foreignIdFor(Purchase::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_segments');
        Schema::dropIfExists('lessons');
    }
};
