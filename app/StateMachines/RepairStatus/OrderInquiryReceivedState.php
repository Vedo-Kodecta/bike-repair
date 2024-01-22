<?php

namespace App\StateMachines\RepairStatus;

use Exception;

class OrderInquiryReceivedState extends BaseRepairStatusState
{

    function pay()
    {
        $this->order->update(['repair_status_id' => 3]);
    }

    function cancel_order()
    {
        $this->order->update(['repair_status_id' => 6]);
    }
}
