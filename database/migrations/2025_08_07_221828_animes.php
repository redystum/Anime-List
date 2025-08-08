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
        Schema::create('animes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique()->primary();
            $table->string('title');
            $table->string('title_jp')->nullable();
            $table->string('title_en')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->text('synopsis')->nullable();
            $table->float('score', 8)->default(0);
            $table->integer('num_scoring_usr')->default(0);
            $table->string('nsfw')->default('white')->only(['white', 'grey', 'black']);
            $table->string('media_type')->default('tv')->only(['tv', 'movie', 'ova', 'special', 'ona', 'music']);
            $table->string('status')->default('finished_airing')->only(['finished_airing', 'currently_airing', 'not_yet_aired']);
            $table->integer('num_episodes')->default(0);
            $table->string('broadcast_weekday')->nullable();
            $table->string('broadcast_time')->nullable();
            $table->integer('average_ep_duration')->default(0);
            $table->text('background')->nullable();

            $table->boolean('favorite')->default(false);
            $table->dateTime('lastFetch')->useCurrent();
            $table->float('localScore', 8)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('viewed')->default(false);
            $table->boolean('watching')->default(false);
            $table->timestamps();
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
