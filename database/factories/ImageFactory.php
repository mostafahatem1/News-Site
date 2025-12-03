<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
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
        $paths = ['test/news8.jpg', 'test/news7.jpg','test/news3.jpg' , 'test/news4.jpg' , 'test/news5.jpg'];
        return [
            'path'=>fake()->randomElement($paths),
        ];
    }
}
