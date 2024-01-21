<?php

namespace App\Interfaces;

interface RepairStatusInterface
{
    function set_price();
    function pay();
    function payment_accepted();
    function finalize_order();
    function cancel_order();
}
