<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect('/')->with('error', 'Akun anda belum terhubung data dokter.');
        }

        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
                                ->where('status', 'pending')
                                ->count();
                                
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
                                ->where('status', 'approved')
                                ->whereDate('appointment_date', now())
                                ->get();

        return view('dokter.dashboard', compact('pendingAppointments', 'todayAppointments'));
    }

    public function indexSchedules()
    {
        $schedules = Auth::user()->doctor->schedules;
        return view('dokter.schedules.index', compact('schedules'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        Schedule::create([
            'doctor_id' => Auth::user()->doctor->id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function indexAppointments()
    {
        $appointments = Appointment::with('user')
                        ->where('doctor_id', Auth::user()->doctor->id)
                        ->where('status', 'pending')
                        ->orderBy('appointment_date', 'asc')
                        ->get();
                        
        return view('dokter.appointments.index', compact('appointments'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        
        $appointment = Appointment::where('doctor_id', Auth::user()->doctor->id)->findOrFail($id);
        $appointment->update([
            'status' => $request->status,
            'admin_note' => $request->note 
        ]);

        return back()->with('success', 'Status janji temu diperbarui.');
    }

    public function createMedicalRecord($appointmentId)
    {
        $appointment = Appointment::with('user')->findOrFail($appointmentId);
        $medicines = Medicine::where('stock', '>', 0)->get();

        return view('dokter.records.create', compact('appointment', 'medicines'));
    }

    public function storeMedicalRecord(Request $request, $appointmentId)
    {
        $request->validate([
            'diagnosis' => 'required',
            'treatment' => 'required',
            'medicines' => 'array', 
            'quantities' => 'array', 
        ]);

        $record = MedicalRecord::create([
            'appointment_id' => $appointmentId,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        if ($request->has('medicines')) {
            foreach ($request->medicines as $index => $medicineId) {
                $quantity = $request->quantities[$index] ?? 1;
                $instruction = $request->instructions[$index] ?? ''; 

                $record->medicines()->attach($medicineId, [
                    'quantity' => $quantity,
                    'instruction' => $instruction
                ]);

                $medicine = Medicine::find($medicineId);
                $medicine->decrement('stock', $quantity);
            }
        }

        $appointment = Appointment::find($appointmentId);
        $appointment->update(['status' => 'completed']);

        return redirect()->route('dokter.dashboard')->with('success', 'Pemeriksaan selesai & Data tersimpan.');
    }
}