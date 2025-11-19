<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->orderBy('hari')
            ->get();
        
        return view('doctor.schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('doctor.schedules.create');
    }

    public function store(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:30',
        ]);

        // Check for overlapping schedules
        $overlap = Schedule::where('doctor_id', $doctor->id)
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->exists();

        if ($overlap) {
            return back()->withErrors(['jam_mulai' => 'Schedule already exists for this time']);
        }

        Schedule::create([
            'doctor_id' => $doctor->id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'durasi' => $request->durasi,
            'is_active' => true,
        ]);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule created successfully');
    }

    public function edit(Schedule $schedule)
    {
        // Ensure doctor can only edit their own schedule
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        return view('doctor.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:30',
            'is_active' => 'boolean',
        ]);

        $schedule->update($request->all());

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule deleted successfully');
    }
}