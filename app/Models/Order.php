<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

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
}
