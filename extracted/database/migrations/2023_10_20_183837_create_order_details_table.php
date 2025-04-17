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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('shop_id');
            $table->integer('product_id');
            $table->String("type");
            $table->Text("product_name")->nullable();
            $table->String("delivery_type")->nullable();
            $table->String("service_type")->nullable();
            $table->decimal('product_per_price', 8, 2);
            $table->integer('product_qty');
            $table->decimal('product_subtotal_price', 8, 2);
            $table->Text("product_image")->nullable();
            $table->date("service_date")->nullable();
            $table->String("hours_required")->nullable();
            $table->Time("start_time")->nullable();
            $table->Time("end_time")->nullable();
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
