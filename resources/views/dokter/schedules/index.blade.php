<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-12 w-auto">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Jadwal Praktek') }}
                </h2>
            </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Form Tambah Jadwal -->
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-lg mb-4 text-gray-800">Tambah Jadwal Baru</h3>
                        <form action="{{ route('dokter.schedules.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <x-input-label value="Hari" />
                                <select name="day" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div>
                                    <x-input-label value="Jam Mulai" />
                                    <input type="time" name="start_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>
                                <div>
                                    <x-input-label value="Jam Selesai" />
                                    <input type="time" name="end_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>
                            </div>
                            <x-primary-button class="w-full justify-center">Simpan Jadwal</x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- List Jadwal -->
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-lg mb-4 text-gray-800">Daftar Jadwal Aktif</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($schedules as $schedule)
                                <div class="border rounded-lg p-4 hover:shadow-md transition bg-gray-50 border-l-4 border-indigo-500">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-lg text-indigo-700">{{ $schedule->day }}</h4>
                                            <p class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                            </p>
                                        </div>
                                        <div class="text-green-600 bg-green-100 px-2 py-1 rounded text-xs font-bold">
                                            Aktif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 col-span-2 text-center py-4">Belum ada jadwal yang diatur.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>