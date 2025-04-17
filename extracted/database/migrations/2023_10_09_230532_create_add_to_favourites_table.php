<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('add_to_favourites', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id');
            $table->Integer('model_id');
            $table->String('model_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_to_favourites');
    }
};
