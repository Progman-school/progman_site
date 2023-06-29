<?php

use App\Models\Uids\Email;
use App\Models\Uids\Telegram;
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
            $table->dropConstrainedForeignIdFor(Telegram::class);
            $table->dropConstrainedForeignIdFor(Email::class);
        });

        Schema::table(app(Telegram::class)->getTable(), function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained();
        });

        Schema::table(app(Email::class)->getTable(), function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(app(User::class)->getTable(), function (Blueprint $table) {
            $table->foreignIdFor(Telegram::class)->constrained();
            $table->foreignIdFor(Email::class)->constrained();
        });
        Schema::table(app(Telegram::class)->getTable(), function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(User::class);
        });
        Schema::table(app(Email::class)->getTable(), function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(User::class);
        });
    }
};
