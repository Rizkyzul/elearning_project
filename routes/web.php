<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordChangeController;

use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;
use App\Http\Controllers\Dosen\MahasiswaController;
use App\Http\Controllers\Dosen\MatkulController as DosenMatkulController;
use App\Http\Controllers\Dosen\MateriController as DosenMateriController;
use App\Http\Controllers\Dosen\TugasController as DosenTugasController;
use App\Http\Controllers\Dosen\NilaiController as DosenNilaiController;
use App\Http\Controllers\Dosen\AbsensiController as DosenAbsensiController;
use App\Http\Controllers\Superadmin\DosenAccountController as SuperadminController;

use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboard;
use App\Http\Controllers\Mahasiswa\MateriController as MahasiswaMateriController;
use App\Http\Controllers\Mahasiswa\TugasController as MahasiswaTugasController;
use App\Http\Controllers\Mahasiswa\AbsensiController as MahasiswaAbsensiController;
use App\Http\Controllers\Mahasiswa\NilaiController as MahasiswaNilaiController;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', function () {
    $user = Auth::user();
    
    if ($user->role === 'dosen' || $user->role === 'superadmin') {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->role === 'mahasiswa') {
        return redirect()->route('mahasiswa.dashboard');
    }
    
    // Fallback (jika ada role aneh)
    return redirect('/');
    
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [PasswordChangeController::class, 'edit'])->name('password.change.edit');
    Route::post('/change-password', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dosen', [SuperadminController::class, 'create'])->name('dosen.create');
        Route::post('/dosen', [SuperadminController::class, 'store'])->name('dosen.store');

    });

    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        
        Route::get('/dashboard', [DosenDashboard::class, 'index'])->name('dashboard');
        
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::post('/mahasiswa', [ MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::post('/mahasiswa/store-manual', [MahasiswaController::class, 'storeManual'])->name('mahasiswa.store.manual');
        Route::post('/mahasiswa/{mahasiswa}/reset-password', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset');

        Route::get('matkul', [DosenMatkulController::class, 'index'])->name('matkul.index');
        Route::post('matkul', [DosenMatkulController::class, 'store'])->name('matkul.store');
        Route::delete('matkul/{matkul}', [DosenMatkulController::class, 'destroy'])->name('matkul.destroy');
 
        Route::prefix('matkul/{matkul}')->group(function () {
            
            Route::resource('materi', DosenMateriController::class, [
                'names' => 'materi',
                'except' => ['show', 'edit', 'update']
            ]);
            
            Route::resource('tugas', DosenTugasController::class, [
                'names' => 'tugas',
                'parameters' => ['tugas' => 'tugas'],
                'except' => ['show', 'edit', 'update']
            ]);
            
            Route::get('tugas/{tugas}/submissions', [DosenTugasController::class, 'showSubmissions'])->name('tugas.show'); 
            Route::get('tugas/{tugas}/jawaban/{jawaban}', [DosenTugasController::class, 'show'])->name('tugas.grade');
            Route::post('tugas/{tugas}/jawaban/{jawaban}', [DosenTugasController::class, 'gradeStore'])->name('tugas.grade.store');

            Route::get('nilai', [DosenNilaiController::class, 'index'])->name('nilai.index');
            Route::get('nilai/{kelas}', [DosenNilaiController::class, 'showKelas'])->name('nilai.showKelas');
            Route::get('nilai/{kelas}/export-excel', [DosenNilaiController::class, 'exportExcel'])->name('nilai.export.excel');
            Route::get('nilai/{kelas}/export-pdf', [DosenNilaiController::class, 'exportPdf'])->name('nilai.export.pdf');
            
            Route::get('absensi', [DosenAbsensiController::class, 'index'])->name('absensi.index');
            Route::get('absensi/sesi/{sesi}', [DosenAbsensiController::class, 'show'])->name('absensi.show');
        });
    });

    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        
        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
        
        Route::prefix('matkul/{matkul}')->group(function () {
            
            Route::get('materi', [MahasiswaMateriController::class, 'index'])->name('materi.index');
            
            Route::get('tugas', [MahasiswaTugasController::class, 'index'])->name('tugas.index');
            
            Route::get('tugas/{tugas}', [MahasiswaTugasController::class, 'show'])->name('tugas.show');
            Route::post('tugas/{tugas}', [MahasiswaTugasController::class, 'submitStore'])->name('tugas.submit');
        });

        Route::get('absensi/scan', [MahasiswaAbsensiController::class, 'showScanner'])->name('absensi.scan');
        Route::post('absensi/scan', [MahasiswaAbsensiController::class, 'storeScan'])->name('absensi.store');
        
        Route::get('nilai', [MahasiswaNilaiController::class, 'index'])->name('nilai.index');
        Route::get('nilai/export-pdf', [MahasiswaNilaiController::class, 'exportPdf'])->name('nilai.export.pdf');
    
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';