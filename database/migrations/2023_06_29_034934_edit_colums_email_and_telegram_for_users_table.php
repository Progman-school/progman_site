<?php

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
        Schema::table(app(User::class)->getTable(), function (Blueprint $table) {
            $table->renameColumn("telegram", "telegram_id");
            $table->renameColumn("email", "email_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(app(User::class)->getTable(), function (Blueprint $table) {
            $table->renameColumn("telegram_id", "telegram");
            $table->renameColumn("email_id", "email");
        });
    }
};
