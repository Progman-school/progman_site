<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::beginTransaction();
        Schema::table('requests', function(Blueprint $table) {
            $table->integer('test_score')->nullable(false);
            $table->foreignId("request_id")->nullable(false)->constrained();
        });
        DB::commit();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::beginTransaction();
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('test_score');
            $table->dropConstrainedForeignId("course_id");
        });
        DB::commit();
    }
};
