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
            $table->foreignId("uid")->nullable(false)->change();
            $table->dropColumn("test_data");
            $table->dropColumn("confirmed");
            $table->dropColumn("test_score");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(app(Request::class)->getTable(), function (Blueprint $table) {
            $table->string("uid", 256)->nullable(false)->change();
            $table->json("test_data")->nullable(false);
            $table->tinyInteger("confirmed")->default(0);
            $table->integer("test_score");
        });
    }
};
