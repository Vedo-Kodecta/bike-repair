<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self ORDER_INQUIRY_SENT()
 * @method static self ORDER_INQUIRY_RECEIVED()
 * @method static self PAYMENT_SENT()
 * @method static self ORDER_IN_PROGRESS()
 * @method static self ORDER_READY()
 * @method static self ORDER_FAILED()
 */
class ERepairStatus extends Enum
{
    const MAP_VALUE = [
        'order_inquiry_sent' => 'ORDER_INQUIRY_SENT',
        'order_inquiry_received' => 'ORDER_INQUIRY_RECEIVED',
        'payment_sent' => 'PAYMENT_SENT',
        'order_in_progress' => 'ORDER_IN_PROGRESS',
        'order_ready' => 'ORDER_READY',
        'order_failed' => 'ORDER_FAILED',
    ];
}
