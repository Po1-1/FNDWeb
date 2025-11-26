<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan durasi ke Events (untuk tahu berapa hari)
        Schema::table('events', function (Blueprint $table) {
            // Kita hitung durasi otomatis dari tgl_mulai & selesai, 
            // tapi kolom ini bantu visualisasi
            $table->integer('total_hari')->default(1)->after('tanggal_selesai');
        });

        // 2. Override Vendor di Mahasiswa (Jika 1 anak beda vendor)
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->foreignId('custom_vendor_id')
                  ->nullable()
                  ->after('kelompok_id')
                  ->constrained('vendors')
                  ->onDelete('set null'); 
                  // Jika diisi, ini menimpa vendor kelompok
        });

        // 3. Tabel Jadwal Kelompok (Per Hari, Per Waktu)
        Schema::create('jadwal_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->onDelete('cascade');
            $table->integer('hari_ke'); // Hari ke-1, ke-2, dst.
            $table->enum('waktu_makan', ['pagi', 'siang', 'sore', 'malam']);
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->timestamps();
            
            // Satu kelompok hanya punya 1 vendor per waktu di hari yg sama
            $table->unique(['kelompok_id', 'hari_ke', 'waktu_makan']); 
        });

        // 4. Update Tabel Distribusi (Header Transaksi)
        Schema::table('distribusis', function (Blueprint $table) {
            $table->integer('hari_ke')->after('kelompok_id')->default(1);
            $table->enum('waktu_makan', ['pagi', 'siang', 'sore', 'malam'])->after('hari_ke');
            // Hapus kolom lama yang tidak relevan jika perlu, atau biarkan
            // $table->dropColumn('jumlah_pengambilan'); // Kita akan hitung dari detail
        });

        // 5. Tabel Detail Distribusi (Siapa yang makan)
        Schema::create('distribusi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_id')->constrained('distribusis')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            // Kita simpan ID Vendor di sini sebagai SNAPSHOT. 
            // Jadi kalau jadwal berubah besok, data sejarah hari ini tetap benar.
            $table->foreignId('vendor_id_snapshot')->constrained('vendors'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_details');
        Schema::dropIfExists('jadwal_kelompoks');
        Schema::table('mahasiswas', fn(Blueprint $table) => $table->dropColumn('custom_vendor_id'));
        Schema::table('events', fn(Blueprint $table) => $table->dropColumn('total_hari'));
        // Rollback distribusis manual jika perlu
    }
};