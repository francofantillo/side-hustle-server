<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_id')->nullable();
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->string('sender_model')->nullable();
            $table->string('receiver_model')->nullable();
            $table->string('product_count')->nullable();
            $table->text('message')->nullable();
            $table->text('file_path')->nullable();
            $table->tinyInteger('type')->default(1)->nullable();
            $table->tinyInteger('message_type')->default(1)->nullable();            
            $table->tinyInteger('is_seen')->default(0)->nullable();
            $table->String('product_type')->nullable();
            $table->String('name')->nullable();
            $table->Double('price')->nullable();
            $table->String('delivery_type')->nullable();
            $table->Date('service_date')->nullable();
            $table->Time('start_time')->nullable();
            $table->Time('end_time')->nullable();
            $table->Text('image')->nullable();
            $table->Text('location')->nullable();
            $table->String('lat')->nullable();
            $table->String('lng')->nullable();
            $table->Text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
