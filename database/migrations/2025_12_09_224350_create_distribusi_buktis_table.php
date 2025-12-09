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
        Schema::create('distribusi_buktis', function (Blueprint $table) {
            $table->id();
            // Relasi ke header transaksi distribusi
            $table->foreignId('distribusi_id')->constrained('distribusis')->onDelete('cascade');
            $table->string('image_path');
            $table->text('catatan')->nullable(); // Catatan dari mentor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_buktis');
    }
};
