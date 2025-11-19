<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $pendingAppointments = Appointment::where('status', 'pending')
            ->with(['patient.user', 'doctor.user', 'doctor.poli', 'schedule'])
            ->orderBy('tanggal_booking')
            ->paginate(15);

        return view('admin.appointments.index', compact('pendingAppointments'));
    }

    public function approve(Appointment $appointment)
    {
        $appointment->update(['status' => 'approved']);
        return back()->with('success', 'Appointment approved successfully');
    }

    public function reject(Request $request, Appointment $appointment)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $appointment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Appointment rejected');
    }

    public function show(Appointment $appointment)
    {
        return view('admin.appointments.show', compact('appointment'));
    }
}