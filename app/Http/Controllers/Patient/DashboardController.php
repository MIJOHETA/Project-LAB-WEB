<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        $latestAppointment = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'doctor.poli', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->first();

        $approvedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->with(['doctor.user', 'schedule'])
            ->orderBy('tanggal_booking')
            ->get();

        $recentMedicalRecords = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'prescriptions.medicine'])
            ->orderBy('tanggal_konsultasi', 'desc')
            ->take(5)
            ->get();

        return view('patient.dashboard', compact('latestAppointment', 'approvedAppointments', 'recentMedicalRecords'));
    }
}