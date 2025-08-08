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
        Schema::create('anime_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->index()->constrained('animes')->onDelete('cascade');
            $table->foreignId('genre_id')->index()->constrained('genres')->onDelete('cascade');
            $table->unique(['anime_id', 'genre_id'], 'anime_genre_unique');
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
