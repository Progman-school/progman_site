<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = true;

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('coupon_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->unique();
            $table->string('prefix', 16)->unique();
            $table->string('use_link', 128);
            $table->string('description_' . \App\Services\TagService::EN_LANGUAGE);
            $table->string('description_' . \App\Services\TagService::RU_LANGUAGE);
        });

        Schema::create('coupon_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->unique();
            $table->string('symbol', 128)->unique();
            $table->enum('symbol_placement', Coupon\CouponUnit::SYMBOL_PLACEMENTS)->default('after');
            $table->string('formula', 512);
        });

        Schema::create('coupon_placements', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->unique();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('serial_number', 64)->unique();
            $table->string('description')->nullable();
            $table->enum('language', \App\Services\TagService::LANG_LIST)->nullable(false);
            $table->enum('method', Coupon::METHODS);
            $table->foreignId('coupon_type_id')->constrained('coupon_types');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable(true)->default(null);
            $table->timestamp('expired_at');
            $table->integer('value');
            $table->foreignId('coupon_unit_id')->constrained('coupon_units');
            $table->integer('max_times')->default(1);
            $table->integer('used_times')->default(0)->nullable(false);
            $table->enum('area_type', ['online', 'offline']);
            $table->string('area', 512);
            $table->foreignId('placement_id')->constrained('coupon_placements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('coupon_placements');
        Schema::dropIfExists('coupon_units');
        Schema::dropIfExists('coupon_types');
    }
};
