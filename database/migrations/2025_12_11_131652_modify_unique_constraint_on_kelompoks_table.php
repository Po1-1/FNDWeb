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
        Schema::table('kelompoks', function (Blueprint $table) {
            // 1. Hapus unique index yang lama pada kolom 'nama' saja
            $table->dropUnique('kelompoks_nama_unique');

            // 2. Tambahkan composite unique index baru
            $table->unique(['event_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompoks', function (Blueprint $table) {
            // 1. Hapus composite unique index yang baru
            $table->dropUnique(['event_id', 'nama']);

            // 2. Kembalikan unique index yang lama
            $table->unique('nama');
        });
    }
};
