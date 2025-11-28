<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;

// Controller Admin, Dokter, Pasien
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\MedicineController;

// Model untuk Rute Perbaikan (Fix)
use App\Models\User;
use App\Models\Doctor;
use App\Models\Poli;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (FINAL - FLAT STRUCTURE)
|--------------------------------------------------------------------------
*/

// --- PUBLIC ---
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/poli', [PublicController::class, 'polis'])->name('public.polis');
Route::get('/dokter', [PublicController::class, 'doctors'])->name('public.doctors');
Route::get('/dokter/{doctor}', [PublicController::class, 'doctorDetail'])->name('public.doctor.detail');

require __DIR__.'/auth.php';

// --- DASHBOARD REDIRECT ---
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'dokter') return redirect()->route('dokter.dashboard');
    if ($role === 'pasien') return redirect()->route('pasien.dashboard');
    abort(404);
})->middleware(['auth', 'verified'])->name('dashboard');

// --- ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Master Data (Poli & Obat)
    Route::resource('polis', PoliController::class)->except(['create', 'edit', 'show']);
    Route::resource('medicines', MedicineController::class)->except(['create', 'edit', 'show']);
});

// --- DOKTER ---
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    
    // Jadwal
    Route::get('/schedules', [DoctorController::class, 'indexSchedules'])->name('schedules.index');
    Route::post('/schedules', [DoctorController::class, 'storeSchedule'])->name('schedules.store');
    
    // Appointments (Pasien Masuk)
    Route::get('/appointments', [DoctorController::class, 'indexAppointments'])->name('appointments.index');
    Route::patch('/appointments/{id}', [DoctorController::class, 'updateAppointmentStatus'])->name('appointments.update');

    // Medical Records (Periksa)
    Route::get('/appointments/{id}/record', [DoctorController::class, 'createMedicalRecord'])->name('records.create');
    Route::post('/appointments/{id}/record', [DoctorController::class, 'storeMedicalRecord'])->name('records.store');
});

// --- PASIEN ---
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    
    // Booking Janji Temu
    Route::get('/appointment/create', [PatientController::class, 'createAppointment'])->name('appointment.create');
    Route::post('/appointment', [PatientController::class, 'storeAppointment'])->name('appointment.store');
});

// --- PROFILE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =========================================================================
// RUTE PERBAIKAN AKUN (TOOLS DEBUGGING)
// =========================================================================

Route::get('/fix-dokter', function () {
    // Cari user dokter berdasarkan email
    $user = User::where('email', 'dokter@rs.com')->first();
    
    if ($user) {
        // Paksa update role jadi 'dokter' (kecil, tanpa spasi)
        $user->role = 'dokter';
        $user->save();
        return "Sukses! Akun dokter@rs.com sudah diperbaiki role-nya.";
    }
    return "User dokter tidak ditemukan.";
});

Route::get('/fix-my-account', function () {
    $user = Auth::user();
    
    if (!$user) {
        return "<h1>Anda belum login!</h1> Silakan login dulu dengan akun manapun (Admin/Pasien/Dokter).";
    }

    // 1. Paksa ubah role akun yg sedang login jadi 'dokter'
    $user->role = 'dokter';
    $user->save();

    // 2. Cek apakah data detail dokter sudah ada? Jika belum, buatkan.
    if (!$user->doctor) {
        // Ambil poli pertama sebagai default
        $poli = Poli::first();
        if (!$poli) {
            $poli = Poli::create(['name' => 'Poli Umum', 'description' => 'Poli Default']);
        }

        Doctor::create([
            'user_id' => $user->id,
            'poli_id' => $poli->id,
            'sip' => 'SIP-AUTO-' . rand(1000, 9999)
        ]);
    }

    return redirect()->route('dokter.dashboard')->with('success', 'Akun Anda BERHASIL diperbaiki menjadi Dokter! Silakan cek Dashboard.');
});