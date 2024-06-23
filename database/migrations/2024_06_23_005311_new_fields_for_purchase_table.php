<?php

use App\Models\Purchase;
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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('name')->after('contact');
            $table->renameColumn('payment_type', 'method');
            $table->double('service_fee')->after('total_price');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('payment_type', Purchase::PAYMENT_TYPES)->after('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('payment_type');
            $table->dropColumn('service_fee');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('method', 'payment_type');
        });
    }
};
