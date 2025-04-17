<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        "cart_id",
        "type",
        "product_id",
        "product_name",
        "product_image",
        "price",
        "delivery_type",
        "service_type",
        "description",
        "qty",
        "service_date",
        "hours_required",
        "start_time",
        "end_time",
    ];
}
