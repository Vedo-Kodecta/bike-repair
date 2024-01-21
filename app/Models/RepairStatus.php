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

    protected $fillable = [
        'status',
    ];
    protected $attributes = [
        'status' => 'order_inquiry_sent',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function state(): RepairStatusInterface
    {
        return match (ERepairStatus::from($this->status)) {
            ERepairStatus::ORDER_INQUIRY_SENT() => new OrderInquirySentState($this),
            ERepairStatus::ORDER_INQUIRY_RECEIVED() => new OrderInquiryReceivedState($this),
            ERepairStatus::PAYMENT_SENT() => new PaymentSentState($this),
            ERepairStatus::ORDER_IN_PROGRESS() => new OrderInProgressState($this),
            ERepairStatus::ORDER_READY() => new OrderReadyState($this),
            ERepairStatus::ORDER_FAILED() => new OrderFailedState($this),
            default => throw new InvalidArgumentException('Invalid status')
        };
    }
}
