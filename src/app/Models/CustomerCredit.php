<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCredit extends Model
{
    use HasFactory;

    protected $table = 'customer_credits';

    protected $fillable = [
        'user_id',
        'amount',
        'reason',
        'custom_order_id',
        'used_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class);
    }
}
