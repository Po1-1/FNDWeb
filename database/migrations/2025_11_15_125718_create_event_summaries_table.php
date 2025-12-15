<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events');
            $table->date('tanggal_summary');

            // Data input manual Admin
            $table->integer('sisa_galon')->default(0);
            $table->text('catatan_harian')->nullable(); 

            // Data snapshot (Dinamis)
            $table->json('rekap_penggunaan_logistik')->nullable();
            $table->json('vendor_bertugas_hari_ini')->nullable();
            $table->json('rekap_penggunaan_makanan')->nullable();
            
            $table->timestamps();

            // Kunci unik agar tidak ada summary ganda per hari per event
            $table->unique(['event_id', 'tanggal_summary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_summaries');
    }
};