<?php

namespace App\StateMachines\RepairStatus;

class OrderInquiryReceivedState extends BaseRepairStatusState
{
    public function pay()
    {
        $this->repairStatus->update(['status' => 'payment_sent']);
    }

    public function cancel_order()
    {
        $this->repairStatus->update(['status' => 'order_failed']);
    }
}
