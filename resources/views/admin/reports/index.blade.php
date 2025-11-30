<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-12 w-auto">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Laporan') }}
                </h2>
            </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Statistik Poli -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Jumlah Pasien per Poliklinik</h3>
                <div class="space-y-4">
                    @foreach($poliStats as $stat)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $stat['name'] }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $stat['total'] }} Pasien</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                @php
                                    // Hitung persentase sederhana untuk lebar bar (max asumsi 100 pasien biar bar kelihatan)
                                    $percent = min(($stat['total'] / 100) * 100, 100); 
                                @endphp
                                <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $stat['total'] > 0 ? $percent : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 2. Kinerja Dokter -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Kinerja Dokter</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Dokter</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Pasien (Selesai)</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($doctorStats as $doc)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $doc->user->name }}</td>
                                    <td class="px-4 py-3 text-sm text-center font-bold text-blue-600">{{ $doc->total_patients }}</td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                            ★ {{ $doc->average_rating }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Penggunaan Obat -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top 10 Penggunaan Obat</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($medicineStats as $med)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-gray-700 font-medium">{{ $med->name }}</span>
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-500">
                                    {{ $med->total_usage }} Unit
                                </span>
                            </li>
                        @empty
                            <li class="py-3 text-gray-500 text-sm italic">Belum ada data penggunaan obat.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>