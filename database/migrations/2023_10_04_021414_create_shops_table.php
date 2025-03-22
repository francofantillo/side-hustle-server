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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id");
            $table->String("name")->nullable();
            $table->String("image")->nullable();
            $table->String("zip_code")->nullable();
            $table->Text("location")->nullable();
            $table->Text("lat")->nullable();
            $table->Text("lng")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
