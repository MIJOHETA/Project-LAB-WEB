<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes (SECURE MODE)
|--------------------------------------------------------------------------
| Middleware Role diaktifkan kembali.
| User hanya bisa mengakses halaman sesuai jabatannya.
*/

// 1. HALAMAN PUBLIK
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/poli', [PublicController::class, 'polis'])->name('public.polis');
Route::get('/dokter', [PublicController::class, 'doctors'])->name('public.doctors');
Route::get('/lihat-dokter/{doctor}', [PublicController::class, 'doctorDetail'])->name('public.doctor.detail');

// 2. DASHBOARD REDIRECTOR
// (Tetap butuh Auth untuk menentukan arah, tapi tidak perlu role check spesifik di sini)
Route::get('/dashboard', function () {
    
    // Auto-Login untuk Testing (Opsional, bisa dihapus saat production)
    if (!Auth::check()) {
        $dokter = User::where('role', 'dokter')->first();
        if ($dokter) {
            Auth::login($dokter);
            return redirect()->route('dokter.dashboard');
        }
    }

    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'dokter') return redirect()->route('dokter.dashboard');
    return redirect()->route('pasien.dashboard');

})->middleware(['auth', 'verified'])->name('dashboard'); 


// 3. ADMIN (Wajib Login + Wajib Role Admin)
// Perhatikan: middleware bertambah jadi ['auth', 'role:admin']
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::resource('polis', PoliController::class);
    Route::resource('medicines', MedicineController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
});


// 4. DOKTER (Wajib Login + Wajib Role Dokter)
// Perhatikan: middleware bertambah jadi ['auth', 'role:dokter']
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/schedules', [DoctorController::class, 'indexSchedules'])->name('schedules.index');
    Route::post('/schedules', [DoctorController::class, 'storeSchedule'])->name('schedules.store');
    
    Route::get('/appointments', [DoctorController::class, 'indexAppointments'])->name('appointments.index');
    Route::put('/appointments/{id}/status', [DoctorController::class, 'updateAppointmentStatus'])->name('appointments.updateStatus');

    Route::get('/appointments/{id}/record', [DoctorController::class, 'createMedicalRecord'])->name('records.create');
    Route::post('/appointments/{id}/record', [DoctorController::class, 'storeMedicalRecord'])->name('records.store');

    Route::get('/profile', [DoctorController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [DoctorController::class, 'updateProfile'])->name('profile.update');
});


// 5. PASIEN (Wajib Login + Wajib Role Pasien)
// Perhatikan: middleware bertambah jadi ['auth', 'role:pasien']
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/appointment/create', [PatientController::class, 'createAppointment'])->name('appointment.create');
    Route::post('/appointment', [PatientController::class, 'storeAppointment'])->name('appointment.store');

    Route::get('/feedback/create/{appointment_id}', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});


// 6. PROFILE UMUM
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('logout', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

require __DIR__.'/auth.php';