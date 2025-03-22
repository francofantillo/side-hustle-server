<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCard extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'customer_id',
        'card_id',
        'last4',
        'type',
        'owner_name',
        'brand_name',
        'brand_logo',
        'is_default',
    ];

    public function cardds() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public static function createCard($customerId, $cardId, $last4, $type, $holderName, $isDefault=0){
        return UserCard::create([
            'user_id'     => Auth::id(),
            'customer_id' => $customerId,
            'card_id'     => $cardId,
            'last4'       => $last4,
            'type'        => $type,
            'owner_name'  => $holderName,
            'is_default'  => $isDefault,
        ]);
    }
}
