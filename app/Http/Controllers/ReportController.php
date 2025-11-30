<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Laporan Jumlah Pasien per Poli (Berdasarkan Janji Temu Selesai)
        $poliStats = Poli::with(['doctors.appointments' => function($query) {
            $query->where('status', 'completed');
        }])->get()->map(function($poli) {
            $totalPatients = $poli->doctors->sum(function($doctor) {
                return $doctor->appointments->count();
            });
            return [
                'name' => $poli->name,
                'total' => $totalPatients
            ];
        });

        // 2. Laporan Kinerja Dokter (Jumlah Pasien & Rating Rata-rata)
        $doctorStats = Doctor::with('user')->withCount(['appointments as total_patients' => function($q) {
            $q->where('status', 'completed');
        }])->get();

        foreach ($doctorStats as $doc) {
            $avgRating = DB::table('feedback')
                ->where('doctor_id', $doc->id)
                ->avg('rating');
            $doc->average_rating = number_format((float)$avgRating, 1) ?? '-';
        }

        // 3. Laporan Penggunaan Obat
        $medicineStats = DB::table('medical_record_medicine')
            ->join('medicines', 'medical_record_medicine.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', DB::raw('SUM(medical_record_medicine.quantity) as total_usage'))
            ->groupBy('medicines.id', 'medicines.name')
            ->orderByDesc('total_usage')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact('poliStats', 'doctorStats', 'medicineStats'));
    }
}