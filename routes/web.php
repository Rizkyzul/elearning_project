<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Import Controller DENGAN ALIAS 'as' ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordChangeController;

// Dosen Controllers
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Dosen\MahasiswaController;
use App\Http\Controllers\Dosen\MateriController as DosenMateriController;
use App\Http\Controllers\Dosen\TugasController as DosenTugasController;
use App\Http\Controllers\Dosen\NilaiController as DosenNilaiController;
use App\Http\Controllers\Dosen\AbsensiController as DosenAbsensiController;

// Mahasiswa Controllers
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Mahasiswa\MateriController as MahasiswaMateriController;
use App\Http\Controllers\Mahasiswa\TugasController as MahasiswaTugasController;
use App\Http\Controllers\Mahasiswa\AbsensiController as MahasiswaAbsensiController;
use App\Http\Controllers\Mahasiswa\NilaiController as MahasiswaNilaiController;


// 1. Route Halaman Awal
Route::get('/', function () {
    return view('auth.login');
});

// 2. Route Pengarah (Setelah Login)
Route::get('/home', function () {
    $user = Auth::user();
    if ($user->role === 'dosen') {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->role === 'mahasiswa') {
        return redirect()->route('mahasiswa.dashboard');
    }
})->middleware(['auth', 'verified'])->name('home');

// 3. Route Ganti Password Wajib
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [PasswordChangeController::class, 'edit'])->name('password.change.edit');
    Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

// 4. Grup Route Utama
Route::middleware(['auth'])->group(function () {

    // 4.1. Grup Dosen
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        
        Route::get('/dashboard', [DosenDashboard::class, 'index'])->name('dashboard');
        
        // Rute Manajemen Mahasiswa
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::post('/mahasiswa', [ MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::post('/mahasiswa/store-manual', [MahasiswaController::class, 'storeManual'])->name('mahasiswa.store.manual');
        Route::post('/mahasiswa/{mahasiswa}/reset-password', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset');
        
        // Rute Nested per Mata Kuliah (Dosen)
        Route::prefix('matkul/{matkul}')->group(function () {
            
            Route::resource('materi', DosenMateriController::class, [
                'names' => 'materi',
                'except' => ['show', 'edit', 'update'] // Hanya index, create, store, destroy
            ]);
            
            // Resource Tugas Dosen (hanya index, create, store, destroy)
            Route::resource('tugas', DosenTugasController::class, [
                'names' => 'tugas',
                'parameters' => ['tugas' => 'tugas'],
                'except' => ['show', 'edit', 'update'] // Menonaktifkan rute GET show/edit/update
            ]);
            
            // Rute Kustom untuk DAFTAR SUBMISSION (Milestone 21)
            Route::get('tugas/{tugas}/submissions', [DosenTugasController::class, 'showSubmissions'])->name('tugas.show'); 
            
            // Rute Kustom untuk MENILAI 1 SUBMISSION (Milestone 22)
            Route::get('tugas/{tugas}/jawaban/{jawaban}', [DosenTugasController::class, 'show'])->name('tugas.grade');
            Route::post('tugas/{tugas}/jawaban/{jawaban}', [DosenTugasController::class, 'gradeStore'])->name('tugas.grade.store');

            // NILAI & EXPORT
            Route::get('nilai', [DosenNilaiController::class, 'index'])->name('nilai.index');
            Route::get('nilai/export-excel', [DosenNilaiController::class, 'exportExcel'])->name('nilai.export.excel');
            Route::get('nilai/export-pdf', [DosenNilaiController::class, 'exportPdf'])->name('nilai.export.pdf');
            
            // ABSENSI
            Route::get('absensi', [DosenAbsensiController::class, 'index'])->name('absensi.index');
            Route::get('absensi/{sesi}', [DosenAbsensiController::class, 'show'])->name('absensi.show');
        });
    }); // <-- Akhir Grup Dosen

    // 4.2. Grup Mahasiswa
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        
        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
        
        // Rute Nested per Mata Kuliah (Mahasiswa)
        Route::prefix('matkul/{matkul}')->group(function () {
            
            Route::get('materi', [MahasiswaMateriController::class, 'index'])->name('materi.index');
            
            Route::get('tugas', [MahasiswaTugasController::class, 'index'])->name('tugas.index');

            // == ROUTE SUBMIT TUGAS MAHASISWA ==
            Route::get('tugas/{tugas}', [MahasiswaTugasController::class, 'show'])->name('tugas.show');
            Route::post('tugas/{tugas}', [MahasiswaTugasController::class, 'submitStore'])->name('tugas.submit');
        });

        // Rute Global Mahasiswa
        Route::get('absensi/scan', [MahasiswaAbsensiController::class, 'showScanner'])->name('absensi.scan');
        Route::post('absensi/scan', [MahasiswaAbsensiController::class, 'storeScan'])->name('absensi.store');
        
        Route::get('nilai', [MahasiswaNilaiController::class, 'index'])->name('nilai.index');
        Route::get('nilai/export-pdf', [MahasiswaNilaiController::class, 'exportPdf'])->name('nilai.export.pdf');
    
    }); // <-- Akhir Grup Mahasiswa

    // 4.3. Route Profile Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // <-- Akhir Grup Auth Utama

// 5. Route Auth Bawaan Breeze
require __DIR__.'/auth.php';