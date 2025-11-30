<x-app-layout>
   <x-slot name="header">
        <div class="flex justify-between items-center">
            
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-12 w-auto">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard Dokter') }}
                </h2>
            </div>

            <a href="{{ route('dokter.profile.edit') }}" class="flex items-center gap-3 hover:bg-gray-100 p-2 rounded-lg transition">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-emerald-600 font-semibold">{{ Auth::user()->doctor->poli->name ?? 'Dokter' }}</div>
                </div>
                @if(Auth::user()->doctor && Auth::user()->doctor->photo)
                    <img src="{{ asset('storage/' . Auth::user()->doctor->photo) }}" class="w-10 h-10 rounded-full object-cover border border-emerald-500">
                @else
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-user-md"></i>
                    </div>
                @endif
            </a>
            
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Card Pending -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-b-4 border-yellow-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Menunggu Persetujuan</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $pendingAppointments ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                            <!-- Heroicon: Clock -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('dokter.appointments.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium inline-flex items-center">
                            Lihat Daftar
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-b-4 border-emerald-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Pasien Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800">{{ isset($todayAppointments) ? count($todayAppointments) : 0 }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                            <!-- Heroicon: User Group / Calendar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-emerald-600 font-medium bg-emerald-50 px-2 py-1 rounded">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tabel Pasien Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-emerald-900">Antrean Pemeriksaan Hari Ini</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                        Prioritas
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    @if(isset($todayAppointments) && count($todayAppointments) > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-emerald-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Pasien</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Keluhan</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-emerald-800 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($todayAppointments as $app)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                        {{ \Carbon\Carbon::parse($app->appointment_date)->format('H:i') }} WIB
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $app->user->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $app->user->phone ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded inline-block max-w-xs truncate">
                                            {{ $app->complaint }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('dokter.records.create', $app->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Periksa
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-12 flex flex-col items-center justify-center text-center">
                            <div class="bg-gray-50 p-4 rounded-full mb-3">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-gray-900 font-medium text-lg">Tidak ada antrean hari ini</h3>
                            <p class="text-gray-500 text-sm mt-1">Nikmati waktu istirahat Anda, Dok!</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>