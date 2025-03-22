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
            $table->String('profession')->after('nick_name')->nullable();
            $table->String('filename')->after('description')->nullable();
            $table->String('file_size')->after('filename')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume', function (Blueprint $table) {
            $table->dropColumn('profession');
            $table->dropColumn('filename');
            $table->dropColumn('file_size');
        });
    }
};
