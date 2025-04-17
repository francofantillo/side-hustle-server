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
        Schema::create('cart_details', function (Blueprint $table) {
            $table->id();
            $table->Integer("cart_id");
            $table->String("type");
            $table->Integer("product_id");
            $table->Text("product_name")->nullable();
            $table->String("delivery_type")->nullable();
            $table->String("service_type")->nullable();
            $table->Double("price")->nullable();
            $table->Integer("qty");
            $table->Text("product_image")->nullable();
            $table->Text("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_details');
    }
};
