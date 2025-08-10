<?php

namespace Database\Factories;

use App\Models\Anime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AnimeFactory extends Factory
{
    protected $model = Anime::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'title' => $this->faker->sentence(3),
            'title_jp' => $this->faker->sentence(3),
            'title_en' => $this->faker->sentence(3),
            'start_date' => $this->faker->dateTimeBetween('-5 years')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('-5 years')->format('Y-m-d'),
            'synopsis' => $this->faker->paragraph,
            'score' => $this->faker->randomFloat(1, 0, 10),
            'num_scoring_usr' => $this->faker->numberBetween(1000, 100000),
            'nsfw' => $this->faker->randomElement(['white', 'grey', 'black']),
            'media_type' => $this->faker->randomElement(['tv', 'movie', 'ova', 'special', 'ona', 'music']),
            'status' => $this->faker->randomElement(['finished_airing', 'currently_airing', 'not_yet_aired']),
            'num_episodes' => $this->faker->numberBetween(1, 50),
            'broadcast_weekday' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']),
            'broadcast_time' => $this->faker->time(),
            'average_ep_duration' => $this->faker->numberBetween(20, 30),
            'background' => $this->faker->paragraph,
            'favorite' => $this->faker->boolean,
            'lastFetch' => $this->faker->dateTime(),
            'localScore' => $this->faker->randomFloat(1, 0, 10),
            'notes' => $this->faker->sentence,
            'completed' => $this->faker->boolean,
            'watching' => $this->faker->boolean,
        ];
    }
}
