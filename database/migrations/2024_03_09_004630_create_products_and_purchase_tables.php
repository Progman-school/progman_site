<?php

use App\Models\Course;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('description_en');
            $table->text('description_ru');
            $table->float('price');
            $table->string('image_url')->nullable()->default(null);
            $table->foreignIdFor(Course::class)->nullable()->default(null)->constrained();
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('request_id')->nullable()->default(null)->constrained();
            $table->foreignId('product_id')->nullable(false)->constrained();
            $table->integer('quantity');
            $table->float('current_product_price');
            $table->float('total_price');
            $table->string('contact');
            $table->foreignId('user_id')->constrained();
            $table->enum('payment_type', \App\Models\Purchase::PAYMENT_TYPES);
            $table->json('payment_details');
            $table->text('comment')->nullable()->default(null);
            $table->foreignId('admin_id')->nullable()->default(null)->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
