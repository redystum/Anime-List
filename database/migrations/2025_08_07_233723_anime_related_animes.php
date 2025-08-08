<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anime_related_animes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->index()->constrained('animes')->onDelete('cascade');
            $table->foreignId('related_anime_id')->index()->constrained('related_animes')->onDelete('cascade');
            $table->unique(['anime_id', 'related_anime_id'], 'anime_related_anime_unique');
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
