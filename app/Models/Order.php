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
use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class Order extends Model
{
    use HasFactory, SearchableTrait;

    protected $fillable = [
        'name',
        'description',
        'price',
        'customer_id',
        'repair_status_id',
        'mechanic_id'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function repairStatus(): BelongsTo
    {
        return $this->belongsTo(RepairStatus::class);
    }

    /**
     * Scope method to create an order
     */
    public function scopeCreateOrder($query, array $data)
    {
        $data = $this->sanitizeOrderData($data);

        return $query->create($data);
    }

    /**
     * Sanitize order data
     */
    protected function sanitizeOrderData(array $data)
    {
        unset($data['price']);

        $data['customer_id'] = $data['customer_id'] ?? 1;
        $data['repair_status_id'] = $data['repair_status_id'] ?? 1;

        return $data;
    }

    public function state(): RepairStatusInterface
    {
        return match (ERepairStatus::from($this->repairStatus->status)) {
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
