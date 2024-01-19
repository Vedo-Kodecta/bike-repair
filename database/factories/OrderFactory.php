<?php

namespace Database\Factories;

use App\Models\RepairStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => fake()->text,
            'price' => $this->faker->randomNumber(2),
            'repair_status_id' => $this->faker->numberBetween(1, 6),
            'customer_id' => User::factory(),
            'mechanic_id' => User::factory(),
        ];
    }
}
