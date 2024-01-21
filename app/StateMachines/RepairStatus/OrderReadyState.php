<?php

namespace App\StateMachines\RepairStatus;

use App\Traits\DefaultRepairStatusMethods;

class OrderReadyState extends BaseRepairStatusState
{

    use DefaultRepairStatusMethods;
}
