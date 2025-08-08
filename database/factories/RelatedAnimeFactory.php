<?php

namespace Database\Factories;

use App\Models\RelatedAnime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RelatedAnimeFactory extends Factory
{
    protected $model = RelatedAnime::class;

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
            'image' => GenImage($this->faker, "225x318"),
            'relation_type' => $this->faker->sentence(2),
        ];
    }
}
