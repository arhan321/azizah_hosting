<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_type',
        'status',
        'payment_method',
        'transaction_id',
        'paid_at',
        // Bank transfer fields
        'bank_name',
        'account_number',
        'account_holder',
        'payment_proof',
        'transfer_date',
        // Verification fields
        'verified_by',
        'verified_at',
        'verification_notes',
        'verification_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'transfer_date' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    const VERIFICATION_PENDING = 'pending';
    const VERIFICATION_VERIFIED = 'verified';
    const VERIFICATION_REJECTED = 'rejected';

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scopes
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Get public URL for payment proof (host-agnostic, Windows-safe path).
     */
    public function getPaymentProofUrlAttribute(): ?string
    {
        $proofPath = $this->payment_proof;

        if (!$proofPath) {
            return null;
        }

        $proofPath = str_replace('\\', '/', $proofPath);

        if (str_starts_with($proofPath, 'http://') || str_starts_with($proofPath, 'https://')) {
            $parsedPath = parse_url($proofPath, PHP_URL_PATH);
            if (is_string($parsedPath) && str_starts_with($parsedPath, '/storage/')) {
                return $parsedPath;
            }

            return $proofPath;
        }

        $proofPath = ltrim($proofPath, '/');
        $proofPath = preg_replace('#^public/#', '', $proofPath) ?? $proofPath;

        if (str_starts_with($proofPath, 'storage/')) {
            return '/' . $proofPath;
        }

        return '/storage/' . $proofPath;
    }
}
