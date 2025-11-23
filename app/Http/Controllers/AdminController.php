<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Appointment;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Halaman Dashboard Admin
    public function dashboard()
    {
        // Statistik untuk Dashboard
        $totalPasien = User::where('role', 'pasien')->count();
        $totalDokter = User::where('role', 'dokter')->count();
        $totalPoli = Poli::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        
        // Ambil 5 janji temu terbaru
        $latestAppointments = Appointment::with(['user', 'doctor.user', 'doctor.poli'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalPasien', 
            'totalDokter', 
            'totalPoli', 
            'pendingAppointments',
            'latestAppointments'
        ));
    }

    // === MANAJEMEN USER (CRUD) ===

    public function indexUsers()
    {
        $users = User::with('doctor.poli')->latest()->paginate(10);
        $polis = Poli::all(); // Untuk dropdown saat tambah dokter
        return view('admin.users.index', compact('users', 'polis'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,dokter,pasien',
            'poli_id' => 'required_if:role,dokter', 
            'sip' => 'required_if:role,dokter', 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->role === 'dokter') {
            Doctor::create([
                'user_id' => $user->id,
                'poli_id' => $request->poli_id,
                'sip' => $request->sip
            ]);
        }

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus diri sendiri!');
        }
        
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}