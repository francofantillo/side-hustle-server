<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        "chat_id",
        "sender_id",
        "receiver_id",
        "sender_model",
        "receiver_model",
        "product_count",
        "message_type",
        "message",
        "file_path",
        "type",
        "is_seen",
        "product_type",
        "name",
        "price",
        "shop_name",
        "delivery_type",
        "service_date",
        "start_time",
        "end_time",
        "image",
        "location",
        "lat",
        "lng",
        "description",
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
