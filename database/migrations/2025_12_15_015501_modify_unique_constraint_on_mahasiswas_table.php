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
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Hapus unique index yang lama
            $table->dropUnique('mahasiswas_nim_unique');

            // Tambahkan composite unique index yang baru
            $table->unique(['event_id', 'nim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Hapus composite unique index
            $table->dropUnique(['event_id', 'nim']);

            // Kembalikan unique index yang lama
            $table->unique('nim');
        });
    }
};
