<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "job_id",
        "owner_id",
        "user_id",
        "bid_amount",
        "status"
    ];

    public function job() {
        return $this->hasOne(Job::class, 'id', 'job_id');
    }

    public function applier() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function owner() {
        return $this->hasOne(Job::class, 'id', 'owner_id');
    }
}
