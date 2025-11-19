<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Poli;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'doctor.poli', 'schedule'])
            ->orderBy('tanggal_booking', 'desc')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $polis = Poli::with('doctors.user')->get();
        return view('patient.appointments.create', compact('polis'));
    }

    public function getDoctorsByPoli($poliId)
    {
        $doctors = Doctor::where('poli_id', $poliId)
            ->with(['user', 'schedules' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $patient = auth()->user()->patient;

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'keluhan_singkat' => 'required|string',
        ]);

        // Check if appointment already exists for this date/time
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('schedule_id', $request->schedule_id)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['tanggal_booking' => 'This slot is already booked']);
        }

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'tanggal_booking' => $request->tanggal_booking,
            'keluhan_singkat' => $request->keluhan_singkat,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment created successfully. Waiting for approval.');
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        return view('patient.appointments.show', compact('appointment'));
    }
}