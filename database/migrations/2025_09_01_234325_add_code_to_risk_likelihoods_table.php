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
        Schema::table('risk_likelihoods', function (Blueprint $table) {
             $table->string('level')->nullable(); // contoh: 1, 2, 3, 4, 5
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::dropIfExists('risk_likelihoods');
    }
};
