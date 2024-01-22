<?php

namespace App\StateMachines\RepairStatus;

class PaymentSentState extends BaseRepairStatusState
{

    function payment_accepted()
    {
        $this->order->update(['repair_status_id' => 4]);
    }
}
