<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "user_id",
        "assigned_user_id",
        "title",
        "location",
        "lat",
        "lng",
        "description",
        "bid_amount",
        "budget",
        "area_code",
        "job_date",
        "job_time",
        "end_time",
        "total_hours",
        "additional_information",
        "status"
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function assign_user() {
        return $this->hasOne(User::class, 'id', 'assigned_user_id');
    }
    public function images() {
        return $this->hasMany(JobImage::class, 'job_id');
    }
}
