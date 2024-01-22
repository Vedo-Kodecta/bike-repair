<?php

namespace App\StateMachines\RepairStatus;

use App\Interfaces\RepairStatusInterface;
use App\Models\Order;
use App\Models\RepairStatus;
use Exception;

abstract class BaseRepairStatusState implements RepairStatusInterface
{

    protected Order $order;

    function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function set_price()
    {
        throw new Exception('Un-allowed action');
    }

    public function pay()
    {
        throw new Exception('Un-allowed action');
    }

    public function payment_accepted()
    {
        throw new Exception('Un-allowed action');
    }

    public function finalize_order()
    {
        throw new Exception('Un-allowed action');
    }

    public function cancel_order()
    {
        throw new Exception('Un-allowed action');
    }
}
