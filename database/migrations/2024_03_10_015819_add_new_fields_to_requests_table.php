<?php

use App\Models\Coupon;
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
            $table->integer('quantity');
            $table->float('current_product_price');
            $table->foreignIdFor(Coupon::class)->nullable()->default(null)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('current_product_price');
            $table->dropForeignIdFor(Coupon::class);
            $table->dropColumn('coupon_id');
        });
    }
};
