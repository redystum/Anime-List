<?php

namespace Database\Factories;

use App\Models\Anime;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'anime_id' => Anime::get()->random()->id,
            'url' => GenImage($this->faker, "225x318"),
            'type' => $this->faker->randomElement(['picture', 'main']),
        ];
    }
}
