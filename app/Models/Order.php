<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "order_no",
        "owner_id",
        "user_id",
        "customer_name",
        "customer_email",
        "sub_total",
        "total",
        'items_total',
        "delivery_address",
        "street",
        "appartment",
        "lat",
        "lng",
        "order_status",
        "status"
    ];

    public function orderDetails() {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function ProductSeller() {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function ProductBuyer() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
