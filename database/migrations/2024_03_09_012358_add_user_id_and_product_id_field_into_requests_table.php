<?php

use App\Models\Product;
use App\Models\User;
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
            $table->foreignIdFor(User::class)->nullable()->default(null)->constrained();
        });
        Schema::table('requests', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)->nullable()->default(null)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeignIdFor(User::class);
            $table->dropColumn('user_id');
            $table->dropForeignIdFor(Product::class);
            $table->dropColumn('product_id');
        });
    }
};
