<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('event_summaries', function (Blueprint $table) {
            // 1. HAPUS KOLOM LAMA YANG HARDCODED
            $table->dropColumn('penggunaan_galon_harian');
            $table->dropColumn('penggunaan_plastik_harian');

            // 2. TAMBAHKAN KOLOM JSON BARU (DINAMIS)
            $table->json('rekap_penggunaan_logistik')->nullable()->after('sisa_galon');
        });
    }
    public function down(): void {
        Schema::table('event_summaries', function (Blueprint $table) {
            $table->dropColumn('rekap_penggunaan_logistik');
            $table->integer('penggunaan_galon_harian')->default(0);
            $table->integer('penggunaan_plastik_harian')->default(0);
        });
    }
};