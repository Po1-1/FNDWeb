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
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            
            // --- KONSOLIDASI ---
            // 'set_vendor' diganti 'vendor_id'
            $table->foreignId('vendor_id')
                  ->nullable() // Boleh kosong saat import
                  ->constrained('vendors')
                  ->onDelete('set null');
            // -------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};
