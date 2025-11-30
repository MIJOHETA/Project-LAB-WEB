<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Wajib import ini
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // --- LOGIKA ANTI-ERROR ---
        // Jika User ini Dokter tapi belum punya data di tabel 'doctors'
        if (!$user->doctor) {
            // 1. Pastikan minimal ada 1 Poli
            $poli = \App\Models\Poli::first();
            if (!$poli) {
                $poli = \App\Models\Poli::create([
                    'name' => 'Poli Umum', 
                    'description' => 'Poli Default'
                ]);
            }

            // 2. Buat Data Dokter Dummy Otomatis
            \App\Models\Doctor::create([
                'user_id' => $user->id,
                'poli_id' => $poli->id,
                'sip'     => 'SIP-AUTO-' . rand(1000,9999)
            ]);
            
            // 3. Refresh user agar relasinya terbaca
            $user = $user->fresh();
        }
        // -------------------------

        $appointmentsToday = Appointment::where('doctor_id', $user->doctor->id)
                                        ->whereDate('appointment_date', now())
                                        ->count();

        // Kirim data lengkap ke view agar tidak error 'undefined variable'
        $todayAppointments = Appointment::where('doctor_id', $user->doctor->id)
                                        ->whereDate('appointment_date', now())
                                        ->get();
        
        $pendingAppointments = Appointment::where('doctor_id', $user->doctor->id)
                                        ->where('status', 'pending')
                                        ->count();

        return view('dokter.dashboard', compact('appointmentsToday', 'todayAppointments', 'pendingAppointments', 'user'));
    }

    // --- FITUR BARU: EDIT PROFILE & FOTO ---
    public function editProfile()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        return view('dokter.profile', compact('user', 'doctor'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto max 2MB
        ]);

        // 1. Update Tabel Users (Nama & HP)
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        // 2. Handle Upload Foto ke Tabel Doctors
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($doctor->photo && Storage::disk('public')->exists($doctor->photo)) {
                Storage::disk('public')->delete($doctor->photo);
            }

            // Simpan foto baru ke folder 'doctors_photos'
            $path = $request->file('photo')->store('doctors_photos', 'public');
            
            $doctor->update(['photo' => $path]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
    // ---------------------------------------

    public function indexSchedules()
    {
        $doctor = Auth::user()->doctor;
        $schedules = Schedule::where('doctor_id', $doctor->id)->get();
        return view('dokter.schedules.index', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        Schedule::create([
            'doctor_id' => Auth::user()->doctor->id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => true
        ]);

        return back()->with('success', 'Jadwal ditambahkan.');
    }

    public function indexAppointments()
    {
        $doctor = Auth::user()->doctor;
        $appointments = Appointment::with('user')
                            ->where('doctor_id', $doctor->id)
                            ->orderBy('appointment_date', 'asc')
                            ->get();

        return view('dokter.appointments.index', compact('appointments'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $request->validate(['status' => 'required']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Status diperbarui.');
    }

    public function createMedicalRecord($id)
    {
        $appointment = Appointment::with('user')->findOrFail($id);
        $medicines = Medicine::all();
        return view('dokter.records.create', compact('appointment', 'medicines'));
    }

    public function storeMedicalRecord(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'required',
            'treatment' => 'required',
            'medicines' => 'array',
        ]);

        $record = MedicalRecord::create([
            'appointment_id' => $id,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        if ($request->has('medicines')) {
            $record->medicines()->attach($request->medicines);
        }

        $appointment = Appointment::find($id);
        $appointment->update(['status' => 'completed']);

        return redirect()->route('dokter.appointments.index')->with('success', 'Rekam medis tersimpan.');
    }
}