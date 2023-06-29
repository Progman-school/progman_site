<?php

use App\Models\Uids\Email;
use App\Models\Uids\Telegram;
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
        Schema::create(app(Telegram::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string("service_uid", 256)->unique()->nullable(false);
            $table->string("service_login")->unique()->nullable();
            $table->json("data");
        });

        Schema::create(app(Email::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->string("service_uid", 256)->unique()->nullable(false);
            $table->string("service_login")->unique()->nullable();
            $table->json("data");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
        Schema::dropIfExists('telegrams');
    }
};
