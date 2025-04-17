<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'user_id',
        "model_name",           
        'payer_email',            
        'stripe_subscription_id',
        'stripe_customer_id',
        'stripe_plan_id',
        'plan_amount',
        'plan_amount_currency',
        'plan_interval',
        'plan_period_start',
        'plan_period_end',
        'payment_method',
        'status'                 
    ];

    public function sub_user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
