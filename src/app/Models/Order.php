<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_type',
        'status',
        'total_price',
        'payment_method',
        'payment_type',
        'payment_status',
        'notes',
        'address',
        'color_preference',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DIKERJAKAN = 'dikerjakan';
    const STATUS_SELESAI = 'selesai';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_FULL = 'full';
    const PAYMENT_DP = 'dp';

    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_DP_PAID = 'dp_paid';
    const PAYMENT_STATUS_FULLY_PAID = 'fully_paid';

    const ORDER_TYPE_CATALOG = 'catalog';
    const ORDER_TYPE_CUSTOM = 'custom';

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customOrder()
    {
        return $this->hasOne(CustomOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function result()
    {
        return $this->hasOne(OrderResult::class);
    }

    /**
     * Scopes
     */
    public function scopeByCustomer($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('order_type', $type);
    }

    /**
     * Calculate amount due based on payment type
     */
    public function getAmountDueAttribute()
    {
        if ($this->payment_type === self::PAYMENT_DP) {
            return $this->total_price * 0.5;
        }
        return $this->total_price;
    }

    /**
     * Calculate remaining amount for DP orders
     */
    public function getAmountRemainingAttribute()
    {
        if ($this->payment_type === self::PAYMENT_DP) {
            return $this->total_price * 0.5;
        }
        return 0;
    }

    /**
     * Check if order is paid
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_STATUS_FULLY_PAID;
    }

    /**
     * Check if DP is paid
     */
    public function isDPPaid()
    {
        return in_array($this->payment_status, [
            self::PAYMENT_STATUS_DP_PAID,
            self::PAYMENT_STATUS_FULLY_PAID,
        ]);
    }
}
