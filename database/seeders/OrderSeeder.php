<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get 10 random customers
        $customers = User::where('role_id', 1)->inRandomOrder()->limit(10)->get();

        // Get 10 random mechanics
        $mechanics = User::where('role_id', 2)->inRandomOrder()->limit(10)->get();

        // Create orders with random customer and mechanic associations
        Order::factory()->count(10)->create([
            'customer_id' => function () use ($customers) {
                return $customers->random()->id;
            },
            'mechanic_id' => function () use ($mechanics) {
                return $mechanics->random()->id;
            },
        ]);
    }
}
