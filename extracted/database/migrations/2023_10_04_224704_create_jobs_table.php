<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->Integer("user_id");
            $table->Integer("assigned_user_id")->nullable();
            $table->String("title");
            $table->double("bid_amount")->default(0.0);
            $table->double("budget")->default(0.0);
            $table->String("area_code")->nullable();
            $table->date("job_date")->nullable();
            $table->time("job_time")->nullable();
            $table->String("total_hours")->nullable();
            $table->Text("location")->nullable();
            $table->String("lat")->nullable();
            $table->String("lng")->nullable();
            $table->Text("description")->nullable();
            $table->Text("additional_information")->nullable();
            $table->String("status")->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
