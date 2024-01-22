<?php

namespace App\Models;

use App\Enums\ERepairStatus;
use App\Interfaces\RepairStatusInterface;
use App\StateMachines\RepairStatus\OrderFailedState;
use App\StateMachines\RepairStatus\OrderInProgressState;
use App\StateMachines\RepairStatus\OrderInquiryReceivedState;
use App\StateMachines\RepairStatus\OrderInquirySentState;
use App\StateMachines\RepairStatus\OrderReadyState;
use App\StateMachines\RepairStatus\PaymentSentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class RepairStatus extends Model
{
    use HasFactory;

    protected $attributes = [
        'status' => 'order_inquiry_sent',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
