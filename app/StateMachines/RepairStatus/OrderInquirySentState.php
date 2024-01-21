<?php

namespace App\StateMachines\RepairStatus;

use App\Traits\DefaultRepairStatusMethods;

class OrderInquirySentState extends BaseRepairStatusState
{

    use DefaultRepairStatusMethods;

    function set_price()
    {
        $this->repairStatus->update(['status' => 'order_inquiry_received']);
    }
}
