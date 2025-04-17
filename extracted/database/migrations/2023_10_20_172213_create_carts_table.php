<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id");
            $table->Integer("owner_id");
            $table->Integer("shop_id");
            $table->Double("sub_total");
            $table->Integer("total_items");
            $table->String("status")->default("Pending");
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
