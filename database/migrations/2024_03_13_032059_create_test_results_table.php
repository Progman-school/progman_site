<?php

use App\Models\Coupon;
use App\Models\User;
use App\Services\UidService;
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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->string('topic');
            $table->string('url')->nullable()->default(null);
            $table->enum('uid_type', UidService::AVAILABLE_TYPES);
            $table->string('contact');
            $table->string('name');
            $table->foreignIdFor(User::class)->nullable()->default(null)->constrained();
            $table->integer('score');
            $table->string('result');
            $table->json('form_data');
            $table->foreignIdFor(Coupon::class)->unique()->nullable()->default(null)->constrained();
            $table->timestamp('result_message_link_clicked_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
