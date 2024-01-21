<?php

namespace App\StateMachines\RepairStatus;

use App\Interfaces\RepairStatusInterface;
use App\Models\RepairStatus;
use Exception;

abstract class BaseRepairStatusState implements RepairStatusInterface
{

    protected RepairStatus $repairStatus;

    function __construct(RepairStatus $repairStatus)
    {
        $this->repairStatus = $repairStatus;
    }

    abstract function set_price();
    abstract function pay();
    abstract function payment_accepted();
    abstract function finalize_order();
    abstract function cancel_order();

    protected function throwException()
    {
        throw new Exception();
    }
}
