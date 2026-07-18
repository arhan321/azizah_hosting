<?php

namespace App\Models;

use App\Support\StoragePath;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'category_id',
        'client_name',
        'location',
        'completion_date',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'completion_date' => 'date',
        'is_featured' => 'boolean',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => StoragePath::publicUrl($value),
            set: fn (?string $value) => StoragePath::normalize($value),
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}