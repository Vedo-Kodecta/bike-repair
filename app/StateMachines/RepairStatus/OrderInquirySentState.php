<?php

namespace App\StateMachines\RepairStatus;

class OrderInquirySentState extends BaseRepairStatusState
{
    public function set_price()
    {
        $this->repairStatus->update(['status' => 'order_inquiry_received']);
    }
}
