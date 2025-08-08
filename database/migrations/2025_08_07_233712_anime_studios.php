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
        Schema::create('anime_studios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->index()->constrained('animes')->onDelete('cascade');
            $table->foreignId('studio_id')->index()->constrained('studios')->onDelete('cascade');
            $table->unique(['anime_id', 'studio_id'], 'anime_studio_unique');
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
