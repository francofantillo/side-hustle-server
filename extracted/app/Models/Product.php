<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "user_id",
        "shop_id",
        "type",
        "name",
        "price",
        "hourly_rate",
        "delivery_type",
        "service_type",
        "location",
        "lat",
        "lng",
        "description",
        "zip_code",
        "additional_information",
    ];

    
    public function shop() {
        return $this->hasOne(Shop::class, 'id', 'shop_id');
    }
    public function product_images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function product_owner() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function orders() {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
