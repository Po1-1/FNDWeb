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
        Schema::create('log_penggunaan_logistiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaris_logistik_id')->constrained('inventaris_logistiks');
            $table->foreignId('user_id')->constrained('users'); // User yang mencatat
            $table->integer('jumlah_digunakan');
            
            // --- KONSOLIDASI ---
            $table->text('catatan')->nullable(); // (cth: "2 galon pecah")
            // -------------------

            $table->date('tanggal_penggunaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_penggunaan_logistiks');
    }
};
