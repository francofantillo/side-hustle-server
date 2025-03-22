<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{

    use SoftDeletes, HasFactory;
    protected $dates = ['deleted_at'];
 
    protected $fillable = [
        "user_id",
        "name",
        "location",
        "lat",
        "lng",
        "date",
        "start_time",
        "end_time",
        "purpose",
        "theme",
        "vendors_list",
        "price",
        "payment_type",
        "available_attractions",
        "status"
    ];

    public function event_images() {
        return $this->hasMany(EventImage::class, 'event_id', 'id');
    }

    public function event_owner() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->event_images()->delete();
        });
    }
}
