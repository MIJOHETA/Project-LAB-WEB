<x-app-layout>
    <x-slot name="header">
       <div class="flex justify-between items-center">
            
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-12 w-auto">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Buat Janji Temu') }}
                </h2>
            </div>
    </x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

    @php
        // Mapping nama hari Indonesia ke index JavaScript (Minggu=0, Senin=1, dst)
        $dayMap = [
            'Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
            'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
        ];
        
        // Ambil jadwal dokter, ubah jadi array angka (misal: Senin & Rabu jadi [1, 3])
        $availableDays = $doctor->schedules->pluck('day')->map(function($day) use ($dayMap) {
            return $dayMap[$day] ?? null;
        })->filter()->values()->toArray();
    @endphp

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-900 mb-4">Konfirmasi Pendaftaran</h3>
                
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl mr-4">
                            Dr
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $doctor->user->name }}</h4>
                            <p class="text-sm text-gray-600">Spesialis {{ $doctor->poli->name }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-blue-200">
                        <p class="text-xs text-gray-500 font-bold uppercase mb-2">Jadwal Praktek:</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($doctor->schedules as $schedule)
                                <span class="bg-white text-blue-600 px-2 py-1 rounded text-xs border border-blue-200 shadow-sm">
                                    {{ $schedule->day }}: {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </span>
                            @empty
                                <span class="text-xs text-red-500">Belum ada jadwal aktif</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <form action="{{ route('pasien.appointment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    <div class="mb-4">
                        <x-input-label for="appointment_date" value="Pilih Tanggal Kunjungan" />
                        
                        <div class="relative">
                            <x-text-input id="appointment_date" 
                                          class="block mt-1 w-full bg-white cursor-pointer" 
                                          type="text" 
                                          name="appointment_date" 
                                          required 
                                          placeholder="Pilih tanggal praktek..." />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-1">*Hanya bisa memilih hari sesuai jadwal dokter (minimal H-1)</p>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="complaint" value="Keluhan Singkat" />
                        <textarea id="complaint" name="complaint" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Contoh: Demam tinggi sudah 3 hari..."></textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('public.doctors') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm font-bold">Batal</a>
                        <x-primary-button class="ml-3">
                            {{ __('Konfirmasi Janji Temu') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script> <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data hari yang diperbolehkan dari PHP
            const allowedDays = @json($availableDays); // Contoh output: [1, 3] untuk Senin, Rabu

            flatpickr("#appointment_date", {
                locale: "id", // Gunakan Bahasa Indonesia
                dateFormat: "Y-m-d", // Format yang dikirim ke database
                minDate: "today", // Tidak boleh pilih tanggal lampau
                disable: [
                    function(date) {
                        // date.getDay() mengembalikan 0 (Minggu) sampai 6 (Sabtu)
                        // Jika hari tersebut TIDAK ada di allowedDays, return true (disable)
                        return !allowedDays.includes(date.getDay());
                    }
                ],
                // Opsional: Langsung blokir tanggal besok jika user mendaftar hari ini (aturan H-1)
                // Hapus baris di bawah jika ingin membolehkan daftar hari ini
                minDate: new Date().fp_incr(1) 
            });
        });
    </script>
</x-app-layout>