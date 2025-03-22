<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddToFavourite extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $fillable = ["user_id", "model_id", "model_name"];

    public function event() {
        return $this->hasOne(Event::class, 'id', 'model_id');
    }

    public function favOwner() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function job() {
        return $this->hasOne(Job::class, 'id', 'model_id');
    }

    public function shop() {
        return $this->hasOne(Shop::class, 'id', 'model_id');
    }
}
