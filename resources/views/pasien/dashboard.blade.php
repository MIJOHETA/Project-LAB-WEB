<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Banner Selamat Datang -->
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                <h3 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}!</h3>
                <p class="opacity-90">Ingin berkonsultasi dengan dokter hari ini?</p>
                <div class="mt-4">
                    <a href="{{ route('public.doctors') }}" class="bg-white text-emerald-600 font-bold py-2 px-4 rounded-full shadow hover:bg-gray-100 transition">
                        Cari Dokter & Buat Janji
                    </a>
                </div>
            </div>

            <!-- Riwayat Janji Temu -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Riwayat Janji Temu</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dokter / Poli</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keluhan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($appointments as $app)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($app->appointment_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-800">{{ $app->doctor->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $app->doctor->poli->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($app->complaint, 30) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($app->status == 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                        @elseif($app->status == 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Disetujui</span>
                                        @elseif($app->status == 'rejected')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @if($app->admin_note)
                                                <div class="text-xs text-red-500 mt-1">Note: {{ $app->admin_note }}</div>
                                            @endif
                                        @elseif($app->status == 'completed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada riwayat janji temu.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>