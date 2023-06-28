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
            $table->string("uid", 256)->nullable(false);
            $table->renameColumn("data", "test_data");
            $table->json("application_data")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(app(Request::class)->getTable(), function (Blueprint $table) {
            $table->dropColumn("uid");
            $table->renameColumn("test_data", "data");
            $table->dropColumn("application_data");
        });
    }
};
