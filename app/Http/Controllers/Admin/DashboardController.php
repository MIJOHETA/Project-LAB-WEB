<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingAppointmentsCount = Appointment::where('status', 'pending')->count();
        
        $todayDoctors = Appointment::where('tanggal_booking', Carbon::today())
            ->where('status', 'approved')
            ->with('doctor.user')
            ->distinct('doctor_id')
            ->get()
            ->pluck('doctor');

        $userStats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'dokter' => User::where('role', 'dokter')->count(),
            'pasien' => User::where('role', 'pasien')->count(),
        ];

        return view('admin.dashboard', compact('pendingAppointmentsCount', 'todayDoctors', 'userStats'));
    }
}