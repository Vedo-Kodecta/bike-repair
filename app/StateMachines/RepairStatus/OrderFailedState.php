<?php

namespace App\StateMachines\RepairStatus;

use App\Traits\DefaultRepairStatusMethods;

class OrderFailedState extends BaseRepairStatusState
{
    use DefaultRepairStatusMethods;
}
