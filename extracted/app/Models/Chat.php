<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps  = false;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "model_id",
        "model_name",
        "user_one",
        "user_two",
        "user_one_model",
        "user_two_model",
        "is_blocked"
    ];

    public function userOne() {
        return $this->hasOne(User::class, 'id', 'user_one');
    }
    public function userTwo() {
        return $this->hasOne(User::class, 'id', 'user_two');
    }
}
