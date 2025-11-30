<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Poli - RS Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-rs.png') }}" alt="RS Sehat" class="h-12 w-auto">
            </a>
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-emerald-600 font-medium transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 mb-10">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Layanan Poliklinik</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($polis as $poli)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition group cursor-pointer" onclick="window.location='{{ route('public.doctors', ['poli_id' => $poli->id]) }}'">
                <div class="h-40 bg-emerald-50 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                    @if($poli->image)
                        <img src="{{ asset('storage/' . $poli->image) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-emerald-300 text-6xl font-bold">{{ substr($poli->name, 0, 1) }}</span>
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-800 group-hover:text-emerald-600 transition">{{ $poli->name }}</h3>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $poli->description }}</p>
                <div class="mt-4 text-emerald-600 font-semibold text-sm">
                    {{ $poli->doctors_count }} Dokter Tersedia &rarr;
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $polis->links() }}
        </div>
    </div>
</body>
</html>