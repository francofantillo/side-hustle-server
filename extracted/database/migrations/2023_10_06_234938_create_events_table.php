<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id");
            $table->String("name")->nullable();
            $table->double("price")->nullable();
            $table->String("payment_type")->nullable();
            $table->date("date")->nullable();
            $table->time("end_time")->nullable();
            $table->time("start_time")->nullable();
            $table->Text("location")->nullable();
            $table->String("lat")->nullable();
            $table->String("lng")->nullable();
            $table->Text("purpose")->nullable();
            $table->Text("theme")->nullable();
            $table->Text("vendors_list")->nullable();
            $table->Text("available_attractions")->nullable();
            $table->String("status")->default('Scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
