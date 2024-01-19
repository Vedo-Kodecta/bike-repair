<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'role_name' => $this->faker->randomElement(['customer', 'mechanic']),
        ];
    }

    public function customer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'customer',
            ];
        });
    }

    public function mechanic()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'mechanic',
            ];
        });
    }
}
