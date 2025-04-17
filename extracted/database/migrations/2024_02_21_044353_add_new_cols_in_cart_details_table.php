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
        Schema::table('cart_details', function (Blueprint $table) {

            $table->date("service_date")->after('qty')->nullable();
            $table->time("hours_required")->after('service_date')->nullable();
            $table->time("start_time")->after('hours_required')->nullable();
            $table->time("end_time")->after('start_time')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cart_details', function (Blueprint $table) {
            $table->dropColumn('service_date');
            $table->dropColumn('hours_required');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            
        });
    }
};
