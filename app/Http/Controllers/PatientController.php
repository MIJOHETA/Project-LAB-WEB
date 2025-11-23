<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function dashboard()
    {
        $appointments = Appointment::with(['doctor.user', 'doctor.poli'])
                        ->where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('pasien.dashboard', compact('appointments'));
    }

    public function createAppointment(Request $request)
    {
        $doctorId = $request->query('doctor_id');
        $doctor = Doctor::with(['user', 'poli', 'schedules'])->findOrFail($doctorId);
        
        return view('pasien.create-appointment', compact('doctor'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'complaint' => 'required|string|max:255',
        ]);

        Appointment::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'complaint' => $request->complaint,
            'status' => 'pending',
        ]);

        return redirect()->route('pasien.dashboard')->with('success', 'Janji temu berhasil dibuat! Menunggu konfirmasi dokter/admin.');
    }
}