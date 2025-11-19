<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $medicalRecords = MedicalRecord::where('doctor_id', $doctor->id)
            ->with(['patient.user', 'prescriptions.medicine'])
            ->orderBy('tanggal_konsultasi', 'desc')
            ->paginate(15);

        return view('doctor.medical-records.index', compact('medicalRecords'));
    }

    // Queue - Show approved appointments for today
    public function queue()
    {
        $doctor = auth()->user()->doctor;
        $today = now()->toDateString();

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->where('tanggal_booking', $today)
            ->with(['patient.user', 'schedule'])
            ->orderBy('tanggal_booking')
            ->get();

        return view('doctor.medical-records.queue', compact('appointments'));
    }

    public function create(Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        // Verify this is doctor's appointment and it's approved
        if ($appointment->doctor_id !== $doctor->id || $appointment->status !== 'approved') {
            abort(403);
        }

        $medicines = Medicine::where('stok', '>', 0)->get();

        return view('doctor.medical-records.create', compact('appointment', 'medicines'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $doctor = auth()->user()->doctor;

        if ($appointment->doctor_id !== $doctor->id || $appointment->status !== 'approved') {
            abort(403);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'tindakan_medis' => 'required|string',
            'catatan' => 'nullable|string',
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Create medical record
            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $doctor->id,
                'diagnosis' => $request->diagnosis,
                'tindakan_medis' => $request->tindakan_medis,
                'catatan' => $request->catatan,
                'tanggal_konsultasi' => now()->toDateString(),
            ]);

            // Create prescriptions
            foreach ($request->medicines as $med) {
                Prescription::create([
                    'medical_record_id' => $me,
                    'medical_record_id' => $medicalRecord->id,
                    'medicine_id' => $med['medicine_id'],
                    'jumlah' => $med['jumlah'],
                ]);

                // Update medicine stock
                $medicine = Medicine::find($med['medicine_id']);
                $medicine->decrement('stok', $med['jumlah']);
            }

            // Update appointment status to 'selesai'
            $appointment->update(['status' => 'selesai']);

            DB::commit();

            return redirect()->route('doctor.medical-records.queue')
                ->with('success', 'Medical record created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create medical record: ' . $e->getMessage()]);
        }
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $doctor = auth()->user()->doctor;

        // Only allow doctor to edit their own records
        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403);
        }

        $medicines = Medicine::where('stok', '>', 0)->get();

        return view('doctor.medical-records.edit', compact('medicalRecord', 'medicines'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $doctor = auth()->user()->doctor;

        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403);
        }

        $request->validate([
            'diagnosis' => 'required|string',
            'tindakan_medis' => 'required|string',
            'catatan' => 'nullable|string',
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Update medical record
            $medicalRecord->update([
                'diagnosis' => $request->diagnosis,
                'tindakan_medis' => $request->tindakan_medis,
                'catatan' => $request->catatan,
            ]);

            // Restore old medicine stock
            foreach ($medicalRecord->prescriptions as $oldPrescription) {
                $medicine = Medicine::find($oldPrescription->medicine_id);
                $medicine->increment('stok', $oldPrescription->jumlah);
            }

            // Delete old prescriptions
            $medicalRecord->prescriptions()->delete();

            // Create new prescriptions
            foreach ($request->medicines as $med) {
                Prescription::create([
                    'medical_record_id' => $medicalRecord->id,
                    'medicine_id' => $med['medicine_id'],
                    'jumlah' => $med['jumlah'],
                ]);

                // Update medicine stock
                $medicine = Medicine::find($med['medicine_id']);
                $medicine->decrement('stok', $med['jumlah']);
            }

            DB::commit();

            return redirect()->route('doctor.medical-records.index')
                ->with('success', 'Medical record updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update medical record: ' . $e->getMessage()]);
        }
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $doctor = auth()->user()->doctor;

        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Restore medicine stock
            foreach ($medicalRecord->prescriptions as $prescription) {
                $medicine = Medicine::find($prescription->medicine_id);
                $medicine->increment('stok', $prescription->jumlah);
            }

            // Delete medical record (prescriptions will be deleted via cascade)
            $medicalRecord->delete();

            // Reset appointment status back to approved
            $medicalRecord->appointment->update(['status' => 'approved']);

            DB::commit();

            return redirect()->route('doctor.medical-records.index')
                ->with('success', 'Medical record deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete medical record']);
        }
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $doctor = auth()->user()->doctor;

        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403);
        }

        return view('doctor.medical-records.show', compact('medicalRecord'));
    }
}