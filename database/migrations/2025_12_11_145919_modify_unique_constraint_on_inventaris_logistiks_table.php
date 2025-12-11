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
        Schema::table('inventaris_logistiks', function (Blueprint $table) {
            // Hapus unique index yang lama
            $table->dropUnique('inventaris_logistiks_nama_item_unique');

            // Tambahkan composite unique index yang baru
            $table->unique(['event_id', 'nama_item']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris_logistiks', function (Blueprint $table) {
            // Hapus composite unique index
            $table->dropUnique(['event_id', 'nama_item']);

            // Kembalikan unique index yang lama
            $table->unique('nama_item');
        });
    }
};
