<?php
// database/factories/RepairStatusFactory.php

namespace Database\Factories;

use App\Models\RepairStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepairStatusFactory extends Factory
{
    protected $model = RepairStatus::class;

    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['order_inquiry_sent', 'order_inquiry_received', 'payment_sent', 'order_in_progress', 'order_ready', 'order_failed']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (RepairStatus $repairStatus) {
            // Your additional configuration logic, if needed
        });
    }

    public function orderInquirySent()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'order_inquiry_sent',
            ];
        });
    }

    public function orderInquiryReceived()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'order_inquiry_received',
            ];
        });
    }

    public function paymentSent()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'payment_sent',
            ];
        });
    }

    public function orderInProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'order_in_progress',
            ];
        });
    }

    public function orderReady()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'order_ready',
            ];
        });
    }

    public function orderFailed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'order_failed',
            ];
        });
    }
}
