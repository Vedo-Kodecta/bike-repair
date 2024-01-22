<?php

namespace App\StateMachines\RepairStatus;


class OrderInquirySentState extends BaseRepairStatusState
{


    function set_price()
    {
        $this->order->update(['repair_status_id' => 2]);
    }
}
