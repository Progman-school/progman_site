<?php

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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name', 128)->unique()->nullable(false)->change();
            $table->text('description_en')->nullable()->change();
            $table->text('description_ru')->nullable()->change();
            $table->renameColumn('price', 'unit_price');
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_name_unique');
            $table->string('name', 256)->change();
            $table->text('description_en')->nullable(false)->change();
            $table->text('description_ru')->nullable(false)->change();
            $table->renameColumn('unit_price', 'price');
            $table->dropColumn('is_active');
        });
    }
};
