<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(app(User::class)->getTable(), function (Blueprint $table) {
            $table->dropColumn("telegram_id");
            $table->dropColumn("email_id");
        });

        Schema::table('telegrams', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained();
        });

        Schema::table('emails', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
