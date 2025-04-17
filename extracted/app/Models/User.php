<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'role_id',
        'first_name',
	    'last_name',
        'name',
        'email',
        'otp',
        'phone',
        'zip_code',
        'country',
        'image',
        'rating',
        'api_token',
        'fcm_token',
        'password',
        'provider_id',
        'provider_name',
        'access_token',
        'is_push_notification',
        'status',
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function eventOwner() {
        return $this->hasOne(Event::class, 'user_id', 'id');
    }

    public function favOwner() {
        return $this->hasOne(AddToFavourite::class, 'user_id', 'id');
    }

    public function myCart() {
        return $this->hasOne(Cart::class, 'user_id', 'id');
    }

    public function ownerCart() {
        return $this->hasOne(Cart::class, 'owner_id', 'id');
    }

    public function interestedUser() {
        return $this->hasOne(InterestedUser::class, 'user_id', 'id');
    }

    public function jobApplier() {
        return $this->hasOne(JobRequest::class, 'user_id', 'id');
    }

    public function jobRequestOwner() {
        return $this->hasOne(JobRequest::class, 'owner_id', 'id');
    }

    public function jobOwner() {
        return $this->hasOne(Job::class, 'user_id', 'id');
    }
    public function JobAssignUser() {
        return $this->hasOne(Job::class, 'assigned_user_id', 'id');
    }

    public function chatUserOne() {
        return $this->hasOne(Chat::class, 'user_one', 'id');
    }
    public function chatUserTwo() {
        return $this->hasOne(Chat::class, 'user_two', 'id');
    }

    public function OrderSeller() {
        return $this->hasOne(Order::class, 'owner_id', 'id');
    }

    public function OrderBuyer() {
        return $this->hasOne(Order::class, 'user_id', 'id');
    }

    public function productKaOwner() {
        return $this->hasOne(Product::class, 'user_id', 'id');
    }

    public function reviewOwner() {
        return $this->hasOne(Review::class, 'task_giver', 'id');
    } 

    public function reviewUser() {
        return $this->hasOne(Review::class, 'tasker', 'id');
    } 

    public function shopUser() {
        return $this->hasOne(Shop::class, 'user_id', 'id');
    }

    public function subcribedUser() {
        return $this->hasOne(Subscription::class, 'user_id', 'id');
    }

    public function userCard() {
        return $this->hasOne(UserCard::class, 'user_id', 'id');
    }

    public function senderNotification() {
        return $this->hasOne(Notifications::class, 'sender_id', 'id');
    }

    public function receiverNotification() {
        return $this->hasOne(Notifications::class, 'receiver_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->eventOwner()->delete();
            $user->favOwner()->delete();
            $user->myCart()->delete();
            $user->ownerCart()->delete();
            $user->interestedUser()->delete();
            $user->jobApplier()->delete();
            $user->jobRequestOwner()->delete();
            $user->jobOwner()->delete();
            $user->JobAssignUser()->delete();
            $user->chatUserOne()->delete();
            $user->chatUserTwo()->delete();
            $user->OrderSeller()->delete();
            $user->OrderBuyer()->delete();
            $user->productKaOwner()->delete();
            $user->reviewOwner()->delete();
            $user->reviewUser()->delete();
            $user->shopUser()->delete();
            $user->subcribedUser()->delete();
            $user->userCard()->delete();
            $user->senderNotification()->delete();
            $user->receiverNotification()->delete();
            

        });
    }
}
