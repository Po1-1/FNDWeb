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
        Schema::create('distribusis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Kasir

            
            $table->foreignId('kelompok_id')
                ->nullable()
                ->constrained('kelompoks')
                ->onDelete('set null');

            $table->enum('tipe', ['makanan', 'logistik']);

            $table->integer('jumlah_pengambilan')->nullable(); // Untuk makanan

            $table->text('catatan')->nullable(); // (deskripsi_pengambilan diganti nama)

            $table->timestamps(); // Waktu pengambilan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};
