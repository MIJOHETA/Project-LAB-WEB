<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        $medicalRecords = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'doctor.poli', 'prescriptions.medicine'])
            ->orderBy('tanggal_konsultasi', 'desc')
            ->paginate(10);

        return view('patient.medical-records.index', compact('medicalRecords'));
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $patient = auth()->user()->patient;

        if ($medicalRecord->patient_id !== $patient->id) {
            abort(403);
        }

        return view('patient.medical-records.show', compact('medicalRecord'));
    }
}