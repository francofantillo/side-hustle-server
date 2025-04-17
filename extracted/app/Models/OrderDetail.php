<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'shop_id',
        'product_id',
        "type",
        "product_name",
        "delivery_type",
        "service_type",
        'product_per_price',
        'product_qty',
        'product_subtotal_price',
        "product_image",
        "delivery_address",
        "street",
        "appartment",
        "lat",
        "lng",
        'status'
    ];

    public function orders() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
