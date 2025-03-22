<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "model_id",
        "model_name",
        "task_giver",
        "tasker",
        "rating",
        "review"
    ];


    public function owner() {
        return $this->hasOne(User::class, 'id', 'task_giver');
    } 

    public function user() {
        return $this->hasOne(User::class, 'id', 'tasker');
    } 
}
