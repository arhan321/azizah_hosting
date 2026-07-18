<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Get design count
     */
    public function getDesignCountAttribute()
    {
        return $this->designs()->count();
    }
}
