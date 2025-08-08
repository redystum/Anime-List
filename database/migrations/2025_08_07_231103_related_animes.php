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
        Schema::create('related_animes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->primary();
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('relation_type')->nullable();
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
