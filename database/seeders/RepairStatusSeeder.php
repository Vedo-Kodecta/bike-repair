<?php

namespace Database\Seeders;

use App\Models\RepairStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepairStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RepairStatus::factory()->create(['status' => 'order_inquiry_sent']);
        RepairStatus::factory()->create(['status' => 'order_inquiry_received']);
        RepairStatus::factory()->create(['status' => 'payment_sent']);
        RepairStatus::factory()->create(['status' => 'order_in_progress']);
        RepairStatus::factory()->create(['status' => 'order_ready']);
        RepairStatus::factory()->create(['status' => 'order_failed']);
    }
}
