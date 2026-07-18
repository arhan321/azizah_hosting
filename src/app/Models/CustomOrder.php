<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'description',
        'material',
        'ornament_level',
        'width',
        'height',
        'dimensions',
        'color_preference',
        'deadline',
        'address',
        'brief',
        'admin_quote',
        'admin_notes',
    ];

    protected $casts = [
        'admin_quote' => 'decimal:2',
        'deadline' => 'date',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
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

    public function files()
    {
        return $this->hasMany(CustomOrderFile::class);
    }

    public function user()
    {
        return $this->order->user();
    }
}
