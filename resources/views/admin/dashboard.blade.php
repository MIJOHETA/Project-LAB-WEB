<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-emerald-800 leading-tight">
                {{ __('Dashboard Admin') }}
            </h2>
            <span class="text-sm text-gray-500">Selamat datang, {{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Pasien -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-b-4 border-blue-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Pasien</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalPasien ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Dokter -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-b-4 border-emerald-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Dokter Aktif</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalDokter ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Poli -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-b-4 border-purple-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Poliklinik</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $totalPoli ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Janji Temu Pending -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border-b-4 border-red-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Perlu Persetujuan</p>
                            <p class="text-3xl font-bold text-red-600">{{ $pendingAppointments ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-red-50 rounded-full text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Janji Temu Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-emerald-900">Janji Temu Terbaru</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded">Real-time</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-emerald-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Dokter & Poli</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Tanggal Kunjungan</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($latestAppointments ?? [] as $appointment)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">
                                            {{ substr($appointment->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $appointment->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $appointment->doctor->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $appointment->doctor->poli->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->status == 'pending')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            Menunggu
                                        </span>
                                    @elseif($appointment->status == 'approved')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                            Disetujui
                                        </span>
                                    @elseif($appointment->status == 'completed')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        <p>Belum ada data janji temu terbaru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>