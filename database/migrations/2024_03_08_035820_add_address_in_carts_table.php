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
        Schema::table('carts', function (Blueprint $table) {
            $table->Text("address")->after("total_items")->nullable();
            $table->String("street")->after("address")->nullable();
            $table->String("appartment")->after("street")->nullable();
            $table->String("lat")->after("appartment")->nullable();
            $table->String("lng")->after("lat")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('street');
            $table->dropColumn('appartment');
            $table->dropColumn('lat');
            $table->dropColumn('lng');

        });
    }
};
