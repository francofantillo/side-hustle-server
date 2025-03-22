<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->integer('owner_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->Double('sub_total');
            $table->Double('total');
            $table->Integer('items_total');
            $table->Text("delivery_address")->nullable();
            $table->String("street")->nullable();
            $table->String("appartment")->nullable();
            $table->String("lat")->nullable();
            $table->String("lng")->nullable();
            $table->enum('order_status', ['', 'paid', 'pending', 'cancelled', 'unpaid', 'completed', 'shipped']);
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
