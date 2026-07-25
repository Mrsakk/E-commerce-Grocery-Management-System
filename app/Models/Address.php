<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'city',
        'district',
        'commune',
        'street',
        'latitude',
        'longitude',
        'landmark',
        'delivery_note',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->street,
            $this->commune,
            $this->district,
            $this->city,
        ]);

        return implode(', ', $parts);
    }

    public function getLabelIconAttribute()
    {
        return match ($this->label) {
            'home' => 'bi-house-door-fill',
            'work' => 'bi-building',
            default => 'bi-geo-alt-fill',
        };
    }

    public function getHasCoordinatesAttribute()
    {
        return ! is_null($this->latitude) && ! is_null($this->longitude);
    }

    public function getGoogleMapsLinkAttribute()
    {
        if ($this->has_coordinates) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($this->full_address);
    }
}
