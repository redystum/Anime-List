<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Anime;
use App\Models\Genre;
use App\Models\Image;
use App\Models\RelatedAnime;
use App\Models\Studio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Genre::factory(50)->create();
        RelatedAnime::factory(50)->create();
        Studio::factory(50)->create();

        Anime::factory(50)->create()->each(function ($anime) {
            $anime->genres()->attach(Genre::inRandomOrder()->take(rand(1, 5))->pluck('id')->toArray());
            $anime->studios()->attach(Studio::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray());
            $anime->relatedAnimes()->attach(RelatedAnime::inRandomOrder()->take(rand(1, 3))->pluck('id')->toArray());
        });

        Image::factory(50)->create();
    }
}
