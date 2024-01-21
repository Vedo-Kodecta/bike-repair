<?php

namespace App\StateMachines\RepairStatus;

class OrderInProgressState extends BaseRepairStatusState
{
    public function finalize_order()
    {
        $this->repairStatus->update(['status' => 'order_ready']);
    }
}
