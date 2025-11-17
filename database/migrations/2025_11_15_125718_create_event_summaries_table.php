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
        Schema::create('event_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->date('tanggal_summary');
            
            // --- KONSOLIDASI ---
            $table->integer('sisa_galon')->default(0); // (Sisa manual dari Admin)

            // Data Snapshot (Kalkulasi)
            $table->integer('penggunaan_galon_harian')->default(0);
            $table->integer('penggunaan_plastik_harian')->default(0);
            
            // Data Snapshot (Teks/JSON)
            $table->text('vendor_bertugas_hari_ini')->nullable(); // Disimpan sebagai JSON
            $table->json('rekap_penggunaan_makanan')->nullable(); // Disimpan sebagai JSON
            
            // Catatan Summary (dari Admin)
            $table->text('catatan_harian')->nullable();
            // -------------------


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_summaries');
    }
};
