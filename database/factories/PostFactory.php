<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->date('Y-m-d H:i:s');

        // Ensure user and category exist, otherwise set to null
        $user = User::inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->unique()->slug,
            'desc' => $this->faker->paragraph(5),
            'status' => rand(0, 1),
            'comment_able' => rand(0, 1),
            'num_of_views' => rand(0, 100),
            'user_id' => $user ? $user->id : \App\Models\User::factory(),
            'category_id' => $category ? $category->id : \App\Models\Category::factory(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
