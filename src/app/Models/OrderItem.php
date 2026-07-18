<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'design_id',
        'price',
        'customization_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'customization_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    /**
     * Get customization data
     */
    public function getCustomizationAttribute()
    {
        return $this->customization_data ?? [];
    }
}
