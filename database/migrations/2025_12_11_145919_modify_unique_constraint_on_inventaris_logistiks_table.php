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
            $table->dropUnique('inventaris_logistiks_nama_item_unique');
            $table->unique(['event_id', 'nama_item']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris_logistiks', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'nama_item']);
            $table->unique('nama_item');
        });
    }
};
