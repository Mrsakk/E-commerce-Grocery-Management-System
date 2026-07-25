<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name', 'description', 'coordinates', 'center_lat',
        'center_lng', 'radius_km', 'delivery_fee', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'coordinates' => 'array',
            'center_lat' => 'float',
            'center_lng' => 'float',
            'radius_km' => 'float',
            'delivery_fee' => 'float',
            'is_active' => 'boolean',
        ];
    }
}
