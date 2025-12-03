<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = ['1.jpg', '2.jpg','3.jpg' , '4.jpg' , '5.jpg','6.jpg', '7.jpg','8.jpg' , '9.jpg' , '10.jpg'];
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'username'=>fake()->userName(),
            'image'=>fake()->randomElement($images),
            'status'=>1,
            'country'=>fake()->country(),
            'city'=>fake()->city(),
            'street'=>fake()->streetAddress(),
            'phone'=>fake()->phoneNumber(),
            'gender' => fake()->randomElement([0, 1]), // 0
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
