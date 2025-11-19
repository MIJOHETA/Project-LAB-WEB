<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;

        $pendingAppointmentsCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();

        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->where('tanggal_booking', Carbon::today())
            ->with(['patient.user', 'schedule'])
            ->get();

        $recentPatients = MedicalRecord::where('doctor_id', $doctor->id)
            ->with('patient.user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('pendingAppointmentsCount', 'todayAppointments', 'recentPatients'));
    }
}