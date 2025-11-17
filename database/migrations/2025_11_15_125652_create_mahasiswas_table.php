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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('prodi');

            // --- KONSOLIDASI ---
            // 'kelompok' dan 'set_vendor' diganti 'kelompok_id'
            $table->foreignId('kelompok_id')
                  ->nullable()
                  ->constrained('kelompoks')
                  ->onDelete('set null');
            // -------------------

            $table->integer('no_urut');
            $table->boolean('is_vegan')->default(false);
            
            // Relasi ke tabel users (untuk Mentor)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
