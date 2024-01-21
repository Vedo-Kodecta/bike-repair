<?php

namespace App\StateMachines\RepairStatus;

use App\Interfaces\RepairStatusInterface;
use App\Models\RepairStatus;
use Exception;

class BaseRepairStatusState implements RepairStatusInterface
{

    function __construct(public RepairStatus $repairStatus)
    {
    }
    public function set_price()
    {
        throw new Exception();
    }
    public function pay()
    {
        throw new Exception();
    }
    public function payment_accepted()
    {
        throw new Exception();
    }
    public function finalize_order()
    {
        throw new Exception();
    }
    public function cancel_order()
    {
        throw new Exception();
    }
}
