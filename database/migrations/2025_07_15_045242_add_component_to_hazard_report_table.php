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
        Schema::table('hazard_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('kondisitidakamen_id')->nullable();
            $table->foreign('kondisitidakamen_id')->references('id')->on('kondisitidakamen')->onDelete('cascade');
            $table->unsignedBigInteger('tindakantidakamen_id')->nullable();
            $table->foreign('tindakantidakamen_id')->references('id')->on('tindakantidakamen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hazard_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('kondisitidakamen_id')->nullable();
            $table->foreign('kondisitidakamen_id')->references('id')->on('kondisitidakamen')->onDelete('cascade');
            $table->unsignedBigInteger('tindakantidakamen_id')->nullable();
            $table->foreign('tindakantidakamen_id')->references('id')->on('tindakantidakamen')->onDelete('cascade');
        });
    }
};
