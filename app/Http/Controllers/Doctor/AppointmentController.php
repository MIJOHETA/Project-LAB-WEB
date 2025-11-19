<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        
        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->with(['patient.user', 'schedule'])
            ->orderBy('tanggal_booking')
            ->get();

        return view('doctor.appointments.index', compact('pendingAppointments'));
    }

    public function approve(Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $appointment->update(['status' => 'approved']);

        return back()->with('success', 'Appointment approved successfully');
    }

    public function reject(Request $request, Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $appointment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Appointment rejected');
    }
}