<?php

use Illuminate\Support\Facades\Route;

// Import Middleware
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureEventIsSelected;

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
use App\Http\Controllers\Developer\TenantController; 

/*
Rute Publik (Guest)
*/

Route::get('/', [GuestController::class, 'index'])->name('home');
Route::get('/apa-itu-fnd', [GuestController::class, 'about'])->name('guest.about');

/*
Rute Autentikasi (Breeze)  Login, Register, Logout, dll.
*/
require __DIR__ . '/auth.php';

/*
 Rute Pengguna Terautentikasi (Semua Role)
*/
Route::middleware('auth')->group(function () {
    // dashboard akan otomatis mengarahkan berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
Rute Khusus DEVELOPER
*/
Route::middleware(['auth', 'role:developer'])
    ->prefix('developer')
    ->name('developer.')
    ->group(function () {
        Route::resource('tenants', TenantController::class)->only(['index', 'create', 'store']);
});


/*
Rute Khusus ADMIN 
*/

Route::middleware(['auth', 'role:admin', 'event.selected'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Event 
        Route::resource('events', EventController::class)->except(['show']);
        Route::get('events/{event}/set-active', [EventController::class, 'setActive'])->name('events.setActive');

        // Import & Reset Mahasiswa 
        Route::get('/mahasiswa/import', [MahasiswaController::class, 'showImportForm'])->name('mahasiswa.import.form');
        Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
        Route::delete('/mahasiswa/destroy-all', [MahasiswaController::class, 'destroyAll'])->name('mahasiswa.destroyAll');

        // Data Master
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::resource('kelompok', KelompokController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('makanan', MakananController::class);
        Route::resource('alergi', AlergiController::class);
        Route::resource('users', UserController::class);
        Route::resource('logistik', InventarisLogistikController::class)->names('logistik');

        // Laporan
        Route::get('summaries/generate', [EventSummaryController::class, 'showGeneratorForm'])->name('summary.generate.form');
        Route::post('summaries/generate', [EventSummaryController::class, 'generateSnapshot'])->name('summary.generate.store');
        Route::resource('summaries', EventSummaryController::class)->only(['index', 'show']);
    });

/*
Rute Role Mentor, Kasir
*/
Route::middleware(['auth', 'role:mentor'])
    ->prefix('mentor')
    ->name('mentor.')
    ->group(function () {
        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
    });

/*
Rute KASIR
*/
Route::middleware(['auth', EnsureUserHasRole::class . ':kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

        // Rute untuk mencatat distribusi
        Route::post('/distribusi/catat-logistik', [DistribusiController::class, 'catatLogistik'])->name('distribusi.logistik.store');

        // Untuk menampilkan halaman checklist (Langkah 2)
        Route::get('/distribusi/checklist', [DistribusiController::class, 'loadChecklist'])
            ->name('distribusi.checklist');

        // Untuk menyimpan data checklist (Langkah 3 - INI YANG ERROR)
        Route::post('/distribusi/store-checklist', [DistribusiController::class, 'storeChecklist'])
            ->name('distribusi.storeChecklist');
    });
