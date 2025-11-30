<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Dokter - RS Sehat Sejahtera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow p-4 mb-8 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-rs.png') }}" alt="RS Sehat Sejahtera" class="h-12 w-auto">
            </a>
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-emerald-600 font-medium transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 mb-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Temukan Dokter Anda</h1>

        <div class="bg-white p-6 rounded-xl shadow-sm mb-8 border border-gray-100">
            <form action="{{ route('public.doctors') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Spesialis</label>
                    <select name="poli_id" class="w-full border-gray-300 rounded-md shadow-sm p-2 border focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Semua Poli --</option>
                        @foreach($polis as $p)
                            <option value="{{ $p->id }}" {{ request('poli_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="bg-emerald-600 text-white font-bold py-2 px-6 rounded-md hover:bg-emerald-700 w-full transition shadow">
                        <i class="fas fa-search mr-2"></i> Tampilkan Dokter
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($doctors as $doc)
            <div class="group bg-white rounded-2xl border border-gray-200 p-4 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col h-full">
                
                <div class="h-64 bg-gray-100 rounded-xl mb-4 overflow-hidden relative shrink-0">
                    @if($doc->photo)
                        <img src="{{ asset('storage/' . $doc->photo) }}" alt="{{ $doc->user->name }}" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-gray-300 bg-gray-50">
                            <i class="fas fa-user-doctor text-6xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-emerald-600/0 group-hover:bg-emerald-600/10 transition-colors duration-300"></div>
                </div>

                <div class="text-center flex-1 flex flex-col">
                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-emerald-600 transition">{{ $doc->user->name }}</h3>
                    <p class="text-emerald-600 text-sm font-bold mb-3 bg-emerald-50 inline-block px-3 py-1 rounded-full mx-auto">{{ $doc->poli->name }}</p>
                    
                    <div class="text-xs text-gray-600 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        @if($doc->schedules->count() > 0)
                            <div class="space-y-1">
                                @foreach($doc->schedules as $sch)
                                    <div class="flex justify-between border-b border-gray-200 last:border-0 pb-1 last:pb-0">
                                        <span class="font-semibold text-gray-700">{{ $sch->day }}</span>
                                        <span>
                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="italic text-red-400">Jadwal belum tersedia</span>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <a href="{{ route('pasien.appointment.create', ['doctor_id' => $doc->id]) }}" class="block w-full bg-white border-2 border-emerald-600 text-emerald-600 text-center py-2.5 rounded-lg font-bold hover:bg-emerald-600 hover:text-white transition duration-300">
                            Buat Janji
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-16">
                <div class="bg-gray-100 inline-block p-6 rounded-full mb-4">
                    <i class="fas fa-user-md text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-700">Tidak Ditemukan</h3>
                <p class="text-gray-500 mt-2">Maaf, dokter dengan spesialisasi tersebut tidak tersedia saat ini.</p>
                <a href="{{ route('public.doctors') }}" class="text-emerald-600 font-bold mt-4 inline-block hover:underline">Reset Pencarian</a>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $doctors->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>