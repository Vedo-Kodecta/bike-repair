<?php

namespace App\StateMachines\RepairStatus;

use App\Traits\DefaultRepairStatusMethods;

class PaymentSentState extends BaseRepairStatusState
{

    use DefaultRepairStatusMethods;

    function payment_accepted()
    {
        $this->repairStatus->update(['status' => 'order_in_progress']);
    }
}
