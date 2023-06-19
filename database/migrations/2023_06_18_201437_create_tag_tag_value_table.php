<?php

use App\Models\Tag;
use App\Models\TagValue;
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
        Schema::create('tag_tag_value', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tag::Class)->constrained();
            $table->foreignIdFor(TagValue::Class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_tag_value');
    }
};
