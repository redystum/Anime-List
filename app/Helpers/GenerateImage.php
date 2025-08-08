<?php

use Faker\Generator as Faker;

if (!function_exists('GenImage')) {
    function GenImage(Faker $faker, $size = "640x640", $text = null): string
    {
        $text = $text ?? $faker->word();
        $bgColor = strtolower($faker->colorName());
        $textColor = strtolower($faker->colorName());

        return "https://placehold.co/{$size}/{$bgColor}/{$textColor}/png?text={$text}";
    }
}
