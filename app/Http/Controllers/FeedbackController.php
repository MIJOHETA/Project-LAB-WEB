<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // --- ADMIN: Melihat Semua Feedback ---
    public function index()
    {
        $feedbacks = Feedback::with(['user', 'doctor.user'])->latest()->paginate(10);
        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    // --- PASIEN: Form Feedback ---
    public function create($appointment_id)
    {
        // Cek apakah appointment valid dan milik user yang login
        $appointment = Appointment::with(['doctor.user', 'doctor.poli'])
            ->where('id', $appointment_id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Cek apakah sudah pernah dinilai
        $existingFeedback = Feedback::where('appointment_id', $appointment->id)->first();
        if ($existingFeedback) {
            return redirect()->route('pasien.dashboard')->with('error', 'Anda sudah memberikan ulasan untuk kunjungan ini.');
        }

        return view('pasien.feedback.create', compact('appointment'));
    }

    // --- PASIEN: Simpan Feedback ---
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Validasi kepemilikan lagi untuk keamanan
        $appointment = Appointment::findOrFail($request->appointment_id);
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        Feedback::create([
            'user_id' => Auth::id(),
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('pasien.dashboard')->with('success', 'Terima kasih atas ulasan Anda!');
    }
}