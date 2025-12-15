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
            $table->dropUnique(['event_id', 'nama']);
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alergis', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'nama']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            
            $table->foreignId('event_id')->nullable()->constrained('events');
            $table->unique(['event_id', 'nama']);
        });
    }
};
