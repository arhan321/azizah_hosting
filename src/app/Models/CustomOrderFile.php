<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_order_id',
        'file_url',
        'file_name',
        'file_size',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor: kembalikan URL publik file, support path relatif & URL lama
     */
    public function getFileUrlAttribute(?string $value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset(\Illuminate\Support\Facades\Storage::url($value));
    }

    /**
     * Relationships
     */
    public function customOrder()
    {
        return $this->belongsTo(CustomOrder::class);
    }
}
