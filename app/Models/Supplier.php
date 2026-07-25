<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_name', 'contact_person', 'phone',
        'email', 'address', 'status'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('supply_price', 'lead_time_days');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
