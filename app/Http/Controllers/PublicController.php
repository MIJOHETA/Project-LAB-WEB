<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index()
    {
        // --- BAGIAN INI KITA MATIKAN/HAPUS ---
        // if (Auth::check()) {
        //     return redirect()->route('dashboard');
        // }
        // -------------------------------------

        // Kode asli untuk menampilkan halaman depan
        $polis = Poli::withCount('doctors')->take(6)->get();
        $doctors = Doctor::with(['user', 'poli'])->take(8)->get();
        
        return view('public.index', compact('polis', 'doctors'));
    }

    public function polis()
    {
        $polis = Poli::withCount('doctors')->paginate(12);
        return view('public.polis', compact('polis'));
    }

    public function doctors(Request $request)
    {
        $query = Doctor::with(['user', 'poli', 'schedules']);

        if ($request->has('poli_id')) {
            $query->where('poli_id', $request->poli_id);
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $doctors = $query->paginate(12);
        $polis = Poli::all();

        return view('public.doctors', compact('doctors', 'polis'));
    }

    public function doctorDetail(Doctor $doctor)
    {
        $doctor->load(['user', 'poli', 'schedules' => function($query) {
            $query->where('is_active', true)->orderBy('hari');
        }]);

        return view('public.doctor-detail', compact('doctor'));
    }
}