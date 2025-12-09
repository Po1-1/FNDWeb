<?php

use Illuminate\Support\Facades\Route;

// Import Middleware Kustom
use App\Http\Middleware\EnsureUserHasRole;

// Import Controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AlergiController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventSummaryController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\MakananController;
use App\Http\Controllers\Admin\InventarisLogistikController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Kasir\KasirDashboardController;
use App\Http\Controllers\Kasir\DistribusiController;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Admin\KelompokController;

/*
|--------------------------------------------------------------------------
| Rute Publik (Guest)
|--------------------------------------------------------------------------
| Rute ini bisa diakses siapa saja, bahkan yang belum login.
*/

Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/apa-itu-fnd', [GuestController::class, 'about'])->name('guest.about');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi (Breeze)
|--------------------------------------------------------------------------
| Ini menangani Login, Register, Logout, dll.
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Rute Pengguna Terautentikasi (Semua Role)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Rute /dashboard akan otomatis mengarahkan berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Rute Khusus ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureUserHasRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('kelompok', KelompokController::class);
        // Rute untuk Import XLSX
        Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
        Route::get('/mahasiswa/import', [MahasiswaController::class, 'showImportForm'])->name('mahasiswa.import.form');

        // Rute CRUD (Resource)
        Route::delete('/mahasiswa/destroy-all', [App\Http\Controllers\Admin\MahasiswaController::class, 'destroyAll'])->name('mahasiswa.destroyAll');
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('makanan', MakananController::class);
        Route::resource('users', UserController::class);

        // --- RUTE YANG DITAMBAHKAN ---
        Route::resource('logistik', InventarisLogistikController::class); // Melengkapi yang tadi
        Route::resource('alergi', AlergiController::class);
        Route::resource('events', EventController::class);

        Route::get('summaries/generate', [EventSummaryController::class, 'showGeneratorForm'])->name('summary.generate.form');
        Route::post('summaries/generate', [EventSummaryController::class, 'generateSnapshot'])->name('summary.generate.store');
        Route::resource('summaries', EventSummaryController::class)->only(['index', 'show']); // Hanya index & show
        // -----------------------------
    });

/*
|--------------------------------------------------------------------------
| Rute Khusus KASIR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureUserHasRole::class . ':kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

        // Rute untuk mencatat distribusi
        // Rute Distribusi Lama (Mungkin masih dipakai untuk logistik)
        Route::post('/distribusi/catat-logistik', [DistribusiController::class, 'catatLogistik'])->name('distribusi.logistik.store');

        // === TAMBAHKAN 2 RUTE INI ===

        // 1. Untuk menampilkan halaman checklist (Langkah 2)
        Route::get('/distribusi/checklist', [DistribusiController::class, 'loadChecklist'])
            ->name('distribusi.checklist');
        // Hasil nama akhir: kasir.distribusi.checklist

        // 2. Untuk menyimpan data checklist (Langkah 3 - INI YANG ERROR)
        Route::post('/distribusi/store-checklist', [DistribusiController::class, 'storeChecklist'])
            ->name('distribusi.storeChecklist');
    });

/*
|--------------------------------------------------------------------------
| Rute Khusus MENTOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureUserHasRole::class . ':mentor'])
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {

        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/kelompok', [MentorDashboardController::class, 'showKelompok'])->name('kelompok.show');

        // TAMBAHKAN RUTE PENCARIAN DI SINI
        Route::get('/search', [MentorDashboardController::class, 'search'])->name('search');
    });
