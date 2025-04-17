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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id");
            $table->Integer("shop_id");
            $table->String("type")->nullable();
            $table->String("name")->nullable();
            $table->double("price")->nullable();
            $table->double("hourly_rate")->nullable();
            $table->String("delivery_type")->nullable();
            $table->String("service_type")->nullable();
            $table->Text("location")->nullable();
            $table->String("lat")->nullable();
            $table->String("lng")->nullable();
            $table->String("zip_code")->nullable();
            $table->Text("description")->nullable();
            $table->Text("additional_information")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
