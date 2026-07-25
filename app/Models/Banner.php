<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en', 'title_km', 'description_en', 'description_km',
        'badge_en', 'badge_km', 'link', 'button_text_en', 'button_text_km',
        'image_path', 'gradient_css', 'icon', 'status', 'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
