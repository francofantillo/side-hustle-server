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
        Schema::table('resume', function (Blueprint $table) {
            $table->String('profile_image')->after('file')->nullable();
            $table->String('actual_name')->after('user_id')->nullable();
            $table->String('nick_name')->after('actual_name')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume', function (Blueprint $table) {
            $table->dropColumn('profile_image');
            $table->dropColumn('actual_name');
            $table->dropColumn('nick_name');
        });
    }
};
