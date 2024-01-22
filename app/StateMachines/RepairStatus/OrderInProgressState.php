<?php

namespace App\StateMachines\RepairStatus;


class OrderInProgressState extends BaseRepairStatusState
{

    function finalize_order()
    {
        $this->order->update(['repair_status_id' => 5]);
    }
}
