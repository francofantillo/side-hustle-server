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
        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('resume', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_cards', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('resume', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('user_cards', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
    }
};
