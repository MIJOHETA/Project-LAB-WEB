<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Dokter - RS Sehat Sejahtera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <!-- Navbar Simple -->
    <nav class="bg-white shadow p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="font-bold text-xl text-emerald-800">RS SEHAT</a>
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-emerald-600">Kembali ke Beranda</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 mb-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Temukan Dokter Anda</h1>

        <!-- Filter & Search -->
        <div class="bg-white p-6 rounded-xl shadow-sm mb-8">
            <form action="{{ route('public.doctors') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Dokter</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: Dr. Budi" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                </div>

                <!-- Filter Poli -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Spesialis</label>
                    <select name="poli_id" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                        <option value="">-- Semua Poli --</option>
                        @foreach($polis as $p)
                            <option value="{{ $p->id }}" {{ request('poli_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex items-end">
                    <button type="submit" class="bg-emerald-600 text-white font-bold py-2 px-6 rounded-md hover:bg-emerald-700 w-full">
                        <i class="fas fa-search mr-2"></i> Tampilkan Dokter
                    </button>
                </div>
            </form>
        </div>

        <!-- List Dokter -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($doctors as $doc)
            <div class="bg-white rounded-xl shadow-sm border p-5 hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-xl text-gray-900">{{ $doc->user->name }}</h3>
                            <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $doc->poli->name }}
                            </span>
                        </div>
                        <div class="bg-gray-100 p-2 rounded-full">
                            <i class="fas fa-user-md text-gray-400 text-2xl"></i>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4">
                        <p class="font-semibold mb-1">Jadwal Praktek:</p>
                        @if($doc->schedules->count() > 0)
                            <ul class="list-disc list-inside">
                                @foreach($doc->schedules as $sch)
                                    <li>{{ $sch->day }}, {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-red-400 italic">Belum ada jadwal aktif</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('pasien.appointment.create', ['doctor_id' => $doc->id]) }}" class="block w-full bg-emerald-600 text-white text-center py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition">
                        Buat Janji Temu
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500">Maaf, dokter yang Anda cari tidak ditemukan.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $doctors->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>