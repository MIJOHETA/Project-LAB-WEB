<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Janji Temu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-bold text-gray-900 mb-4">Konfirmasi Pendaftaran</h3>
                
                <!-- Detail Dokter -->
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
                    
                    <!-- Jadwal Dokter -->
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

                <!-- Form Booking -->
                <form action="{{ route('pasien.appointment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                    <div class="mb-4">
                        <x-input-label for="appointment_date" value="Pilih Tanggal Kunjungan" />
                        <x-text-input id="appointment_date" class="block mt-1 w-full" type="date" name="appointment_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                        <p class="text-xs text-gray-500 mt-1">*Pendaftaran minimal H-1</p>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="complaint" value="Keluhan Singkat" />
                        <textarea id="complaint" name="complaint" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required placeholder="Contoh: Demam tinggi sudah 3 hari..."></textarea>
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
</x-app-layout>