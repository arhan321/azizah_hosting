<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OrderResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'file_url',
        'notes',
        'download_token',
        'expires_at',
        'downloaded_at',
        'download_count',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method - auto generate token and set expiration
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->download_token) {
                $model->download_token = Str::random(40);
            }
            if (!$model->expires_at) {
                $model->expires_at = Carbon::now()->addDays(7);
            }
        });
    }

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if download link is expired
     */
    public function isExpired()
    {
        return now()->isAfter($this->expires_at);
    }

    /**
     * Check if download link is still valid
     */
    public function isValid()
    {
        return !$this->isExpired();
    }

    /**
     * Get days remaining for download
     */
    public function getDaysRemainingAttribute()
    {
        return now()->diffInDays($this->expires_at, false);
    }

    /**
     * Get a guaranteed-absolute download URL
     */
    public function getDownloadUrlAttribute(): ?string
    {
        if (!$this->file_url) return null;
        if (str_starts_with($this->file_url, 'http')) {
            return $this->file_url;
        }
        // Legacy relative /storage/... format → convert to public disk URL
        $path = preg_replace('#^/storage/#', '', $this->file_url);
        return Storage::disk('public')->url($path);
    }
}
