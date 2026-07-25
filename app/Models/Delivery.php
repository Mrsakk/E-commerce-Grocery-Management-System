<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_id', 'delivery_staff_id', 'delivery_date',
        'delivery_status', 'tracking_no', 'received_by', 'failed_delivery_reason'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'delivery_staff_id');
    }
}
