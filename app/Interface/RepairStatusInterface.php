<?php

namespace App\Interfaces;

interface RepairStatusInterface
{
    public function set_price();
    public function pay();
    public function payment_accepted();
    public function finalize_order();
    public function cancel_order();
}
