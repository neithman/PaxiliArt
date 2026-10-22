<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['artist_id', 'title', 'slug', 'description', 'category', 'price', 'cover_image', 'digital_file_path', 'dimensions', 'status', 'sales_count', 'is_featured'])]
class Artwork extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sales_count' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCoverUrlAttribute(): string
    {
        return str_starts_with($this->cover_image, 'artworks/covers/')
            ? Storage::disk('public')->url($this->cover_image)
            : asset($this->cover_image);
    }
}
