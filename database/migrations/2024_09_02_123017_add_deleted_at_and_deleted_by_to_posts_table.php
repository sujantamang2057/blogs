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
        Schema::table('latest_blogs', function (Blueprint $table) {
            //
            $table->timestamp('deleted_at')->nullable(); // To store the deletion timestamp
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('latest_blogs', function (Blueprint $table) {
            //
        });
    }
};
