<?php

namespace App\Enums;

class EStateMachineFunctions
{
    const SET_PRICE = 'SET_PRICE';
    const PAY = 'PAY';
    const PAYMENT_ACCEPTED = 'PAYMENT_ACCEPTED';
    const FINALIZE_ORDER = 'FINALIZE_ORDER';
    const CANCEL_ORDER = 'CANCEL_ORDER';
}
