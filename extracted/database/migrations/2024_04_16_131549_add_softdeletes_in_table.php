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
        Schema::table('add_to_favourites', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('interested_users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('job_requests', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_to_favourites', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('carts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('interested_users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('job_requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

    }
};
