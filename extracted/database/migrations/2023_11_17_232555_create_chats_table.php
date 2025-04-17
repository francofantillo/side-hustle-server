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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->integer('model_id')->nullable();
            $table->String('model_name')->nullable();
            $table->integer('user_one')->nullable();
            $table->integer('user_two')->nullable();
            $table->string('user_one_model')->nullable();
            $table->string('user_two_model')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
