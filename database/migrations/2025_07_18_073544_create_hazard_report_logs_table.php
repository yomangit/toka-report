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
        Schema::create('hazard_report_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hazard_report_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Moderator / ERM
            $table->string('action'); // contoh: updated_status, assigned_erm, added_comment
            $table->text('description')->nullable(); // optional detail
            $table->json('old_values')->nullable(); // sebelum update (optional)
            $table->json('new_values')->nullable(); // setelah update (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_report_logs');
    }
};
