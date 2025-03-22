<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('delete_accounts', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id");
            $table->String("name")->nullable();
            $table->Text("image")->nullable();
            $table->TinyInteger("status")->default(0);
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('delete_accounts');
    }
};
