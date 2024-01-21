<?php

namespace App\StateMachines\RepairStatus;

class PaymentSentState extends BaseRepairStatusState
{
    public function payment_accepted()
    {
        $this->repairStatus->update(['status' => 'order_in_progress']);
    }
}
