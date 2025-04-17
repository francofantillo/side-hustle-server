<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "user_id",
        "name",
        "image",
        "zip_code",
        "location",
        "lat",
        "lng"
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
