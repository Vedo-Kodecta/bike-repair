<?php

namespace App\StateMachines\RepairStatus;

use App\Traits\DefaultRepairStatusMethods;

class OrderInProgressState extends BaseRepairStatusState
{

    use DefaultRepairStatusMethods;

    function finalize_order()
    {
        $this->repairStatus->update(['status' => 'order_ready']);
    }
}
