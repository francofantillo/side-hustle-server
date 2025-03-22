<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "user_id",
        "owner_id",
        "shop_id",
        "sub_total",
        "total_items",
        "address",
        "street",
        "appartment",
        "lat",
        "lng",
        "status"
    ];

    public function cart_details() {
        return $this->hasMany(CartDetail::class, 'cart_id');
    } 

    public function personalCart() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
    public function carttOwner() {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
