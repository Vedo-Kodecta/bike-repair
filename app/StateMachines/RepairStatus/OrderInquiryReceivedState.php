<?php

namespace App\StateMachines\RepairStatus;

use App\Traits\DefaultRepairStatusMethods;
use Exception;

class OrderInquiryReceivedState extends BaseRepairStatusState
{

    use DefaultRepairStatusMethods;

    public function set_price()
    {
        throw new Exception('ovde nekim cudom');
    }

    function pay()
    {
        $this->repairStatus->update(['status' => 'payment_sent']);
    }

    function cancel_order()
    {
        $this->repairStatus->update(['status' => 'order_failed']);
    }
}
