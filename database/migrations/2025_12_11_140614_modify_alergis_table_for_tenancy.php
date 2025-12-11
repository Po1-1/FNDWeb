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
        Schema::table('alergis', function (Blueprint $table) {
            // 1. Hapus composite index yang lama terlebih dahulu
            // Index ini menggunakan kolom event_id dan nama
            $table->dropUnique(['event_id', 'nama']);

            // 2. Hapus foreign key dan kolom event_id
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');

            // 3. Tambahkan kolom tenant_id yang baru
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
            
            // 4. Buat composite index yang baru menggunakan tenant_id dan nama
            $table->unique(['tenant_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alergis', function (Blueprint $table) {
            // Urutan dibalik untuk rollback
            $table->dropUnique(['tenant_id', 'nama']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            
            $table->foreignId('event_id')->nullable()->constrained('events');
            $table->unique(['event_id', 'nama']);
        });
    }
};
