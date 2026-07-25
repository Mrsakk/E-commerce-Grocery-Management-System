<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'qty_in_stock', 'reorder_level', 'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'qty_in_stock' => 'integer',
            'reorder_level' => 'integer',
            'last_updated' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
