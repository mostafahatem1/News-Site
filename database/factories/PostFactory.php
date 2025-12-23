<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        // Realistic and professional news article titles
        $titles = [
            'New Developments in Artificial Intelligence Reshape the Market',
            'Experts Warn About the Impact of Social Media on Mental Health',
            'Arab Economy Shows Remarkable Growth in Q4',
            'Revolutionary Innovations in Renewable Energy Technologies',
            'The Critical Role of Digital Education in Modern Times',
            'Major Investments Flow Into FinTech Sector in Middle East',
            'Opportunities and Challenges: The Future of Freelancing',
            'How to Choose the Best E-Commerce Platforms for Small Stores',
            'Top Digital Marketing Trends to Watch in 2025',
            'Digital Transformation: How Companies Are Preparing for the Future',
        ];

        // Professional and detailed descriptions
        $descriptions = [
            'The technology sector has witnessed rapid developments over recent months, with major companies investing billions in artificial intelligence. This article highlights the most important breakthroughs and future predictions.',
            'Recent scientific studies show that excessive use of social media significantly impacts mental health. We provide practical tips to reduce negative effects.',
            'The Arab economy is experiencing notable growth thanks to substantial investments and wise economic policies. Read the full details in this comprehensive report.',
            'Renewable energy represents the future, with significant advances in solar panels and wind turbines. Discover how these technologies can transform our lives.',
            'Digital education is no longer optional but a necessity in today\'s world. This article explains the importance of transitioning to e-learning and its benefits.',
            'The technology sector offers unprecedented opportunities for developers and entrepreneurs. Learn about the latest trends and how to leverage them.',
            'Remote work has revolutionized the way companies operate globally. Explore best practices for managing distributed teams effectively.',
            'Cybersecurity threats are increasing at an alarming rate. Understand the key measures to protect your business from attacks.',
            'Cloud computing has become essential for modern businesses. Discover the advantages and how to migrate successfully.',
            'Blockchain technology extends beyond cryptocurrency with applications in supply chain and healthcare. Learn about real-world implementations.',
        ];

        $date = $this->faker->dateTimeBetween('-6 months', 'now');

        $user = User::inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();

        return [
            'title' => $this->faker->randomElement($titles),
            'slug' => $this->faker->unique()->slug,
            'desc' => $this->faker->randomElement($descriptions),
            'status' => $this->faker->randomElement([0, 1]), // 1 = published, 0 = draft
            'comment_able' => $this->faker->randomElement([0, 1]),
            'num_of_views' => $this->faker->numberBetween(10, 5000),
            'user_id' => $user ? $user->id : User::factory(),
            'category_id' => $category ? $category->id : Category::factory(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    /**
     * Create a published post
     */
    public function published(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1,
            'num_of_views' => $this->faker->numberBetween(100, 5000),
        ]);
    }

    /**
     * Create a draft post
     */
    public function draft(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
            'num_of_views' => 0,
        ]);
    }
}
