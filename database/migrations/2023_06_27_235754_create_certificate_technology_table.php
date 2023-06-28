<?php

use App\Models\Certificate;
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
        Schema::create('certificate_technology', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Certificate::class)->constrained();
            $table->foreignIdFor(Technology::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_technology');
    }
};
