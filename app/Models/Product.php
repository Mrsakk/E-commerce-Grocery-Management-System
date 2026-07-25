<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'product_name', 'description', 'price',
        'unit', 'image', 'brand', 'expiry_date', 'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class)->withPivot('supply_price', 'lead_time_days');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function averageRating(): float
    {
        $avg = $this->reviews()->where('is_approved', true)->avg('rating');

        return $avg ? round($avg, 1) : 0.0;
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('images/products/'.$this->image))) {
            return asset('images/products/'.$this->image);
        }

        $name = strtolower($this->product_name);
        $mappings = [
            'spinach' => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=300&q=80',
            'cabbage' => 'https://images.unsplash.com/photo-1589135753103-e8370ef6143a?auto=format&fit=crop&w=300&q=80',
            'bok choy' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=300&q=80',
            'morning glory' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=300&q=80',
            'tomato' => 'https://images.unsplash.com/photo-1595855759920-86582396756a?auto=format&fit=crop&w=300&q=80',
            'cucumber' => 'https://images.unsplash.com/photo-1449300079323-02e209d9d3a6?auto=format&fit=crop&w=300&q=80',
            'carrot' => 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&w=300&q=80',
            'banana' => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?auto=format&fit=crop&w=300&q=80',
            'mango' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=300&q=80',
            'dragon fruit' => 'https://images.unsplash.com/photo-1527324688151-0e627063f2b1?auto=format&fit=crop&w=300&q=80',
            'orange' => 'https://images.unsplash.com/photo-1547514701-42782101795e?auto=format&fit=crop&w=300&q=80',
            'coconut' => 'https://images.unsplash.com/photo-1589135753103-e8370ef6143a?auto=format&fit=crop&w=300&q=80',
            'chicken' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=300&q=80',
            'pork' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=300&q=80',
            'beef' => 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=300&q=80',
            'fish' => 'https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=300&q=80',
            'shrimp' => 'https://images.unsplash.com/photo-1559737605-de6a255fda00?auto=format&fit=crop&w=300&q=80',
            'squid' => 'https://images.unsplash.com/photo-1534080391025-a77af6ebc1a6?auto=format&fit=crop&w=300&q=80',
            'milk' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=300&q=80',
            'egg' => 'https://images.unsplash.com/photo-1506976785307-8732e854ad03?auto=format&fit=crop&w=300&q=80',
            'yogurt' => 'https://images.unsplash.com/photo-1571244856353-fb0e52187e15?auto=format&fit=crop&w=300&q=80',
            'rice' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=300&q=80',
            'noodle' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=300&q=80',
            'water' => 'https://images.unsplash.com/photo-1525385133772-2551978d885a?auto=format&fit=crop&w=300&q=80',
            'sauce' => 'https://images.unsplash.com/photo-1585325701956-60dd9c8553bc?auto=format&fit=crop&w=300&q=80',
        ];

        foreach ($mappings as $key => $url) {
            if (str_contains($name, $key)) {
                return $url;
            }
        }

        return 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=300&q=80';
    }
}
