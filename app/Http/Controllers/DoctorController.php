<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;

class DoctorController extends Controller
{
    /**
     * Menampilkan Dashboard Dokter
     */
    public function dashboard()
    {
        // Ambil data dokter dari user yang sedang login
        $user = Auth::user();
        
        // Pastikan user ini benar-benar punya data dokter
        if (!$user->doctor) {
            return redirect()->route('home')->with('error', 'Data profil dokter tidak ditemukan.');
        }

        // Contoh data untuk dashboard
        $appointmentsToday = Appointment::where('doctor_id', $user->doctor->id)
                                        ->whereDate('appointment_date', now())
                                        ->count();

        // Pastikan Anda sudah membuat view: resources/views/dokter/dashboard.blade.php
        return view('dokter.dashboard', compact('appointmentsToday'));
    }

    /**
     * Menampilkan Halaman Jadwal (Schedules)
     */
    public function indexSchedules()
    {
        $doctor = Auth::user()->doctor;
        $schedules = Schedule::where('doctor_id', $doctor->id)->get();
        
        return view('dokter.schedules.index', compact('schedules'));
    }

    /**
     * Menyimpan Jadwal Baru
     */
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

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Menampilkan Daftar Janji Temu Pasien (Appointments)
     */
    public function indexAppointments()
    {
        $doctor = Auth::user()->doctor;
        
        // Ambil janji temu milik dokter ini
        $appointments = Appointment::with('user') // load data pasien
                            ->where('doctor_id', $doctor->id)
                            ->orderBy('appointment_date', 'asc')
                            ->get();

        return view('dokter.appointments.index', compact('appointments'));
    }

    /**
     * Update Status Janji Temu (Misal: dari 'pending' ke 'processed')
     */
    public function updateAppointmentStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Validasi status
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Status janji temu diperbarui.');
    }

    /**
     * Form Rekam Medis (Medical Record)
     */
    public function createMedicalRecord($id)
    {
        $appointment = Appointment::with('user')->findOrFail($id);
        $medicines = Medicine::all(); // Untuk pilihan obat

        return view('dokter.records.create', compact('appointment', 'medicines'));
    }

    /**
     * Simpan Rekam Medis
     */
    public function storeMedicalRecord(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'medicines' => 'array', // Array ID obat
        ]);

        $record = MedicalRecord::create([
            'appointment_id' => $id,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        // Simpan relasi obat jika ada
        if ($request->has('medicines')) {
            $record->medicines()->attach($request->medicines);
        }

        // Update status appointment jadi selesai
        $appointment = Appointment::find($id);
        $appointment->update(['status' => 'completed']);

        return redirect()->route('dokter.appointments.index')->with('success', 'Rekam medis berhasil disimpan.');
    }
}