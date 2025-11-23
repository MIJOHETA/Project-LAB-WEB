<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RS Sehat Sejahtera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-2">
                    <div class="bg-emerald-600 text-white p-2 rounded-lg">
                        <i class="fas fa-heartbeat text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-emerald-800 leading-none">RS SEHAT</h1>
                        <span class="text-xs text-gray-500 font-medium tracking-wider">SEJAHTERA</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8 font-medium text-gray-600">
                    <a href="{{ route('home') }}" class="text-emerald-600 font-bold">Beranda</a>
                    <a href="{{ route('public.doctors') }}" class="hover:text-emerald-600 transition">Cari Dokter</a>
                    <a href="{{ route('public.polis') }}" class="hover:text-emerald-600 transition">Layanan Poli</a>
                </div>

                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-full font-semibold hover:bg-emerald-700 transition">
                            Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-emerald-600 font-semibold px-4">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-full font-semibold hover:bg-emerald-700 transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-white py-16 lg:py-24">
        <div class="container mx-auto px-4 flex flex-col-reverse lg:flex-row items-center">
            <div class="lg:w-1/2 mt-10 lg:mt-0">
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Kesehatan Anda <br> <span class="text-emerald-600">Prioritas Kami</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-lg">
                    Daftar rawat jalan lebih mudah dan cepat melalui website kami. Temukan dokter spesialis terbaik untuk keluarga Anda.
                </p>
                <a href="{{ route('public.doctors') }}" class="bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg inline-block">
                    Buat Janji Temu Sekarang
                </a>
            </div>
            <div class="lg:w-1/2 flex justify-center">
                <!-- Placeholder Image -->
                <img src="https://img.freepik.com/free-vector/doctors-concept-illustration_114360-1515.jpg?w=826&t=st=1709440000~exp=1709440600~hmac=..." alt="Ilustrasi Dokter" class="w-full max-w-md">
            </div>
        </div>
    </header>

    <!-- Poli Unggulan Section -->
    <section class="py-16 bg-emerald-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Poli & Layanan Unggulan</h2>
                <p class="text-gray-600 mt-2">Pilih layanan kesehatan sesuai kebutuhan Anda</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($polis as $poli)
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition text-center">
                    <div class="w-16 h-16 mx-auto bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 text-2xl mb-4">
                        @if($poli->image)
                            <img src="{{ asset('storage/' . $poli->image) }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <i class="fas fa-stethoscope"></i>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-800">{{ $poli->name }}</h4>
                    <p class="text-xs text-gray-500 mt-1">{{ $poli->doctors_count ?? 0 }} Dokter</p>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('public.polis') }}" class="text-emerald-600 font-bold hover:underline">Lihat Semua Poli &rarr;</a>
            </div>
        </div>
    </section>

    <!-- Dokter Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Dokter Spesialis Kami</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($doctors as $doc)
                <div class="border rounded-xl p-4 hover:shadow-lg transition">
                    <div class="h-48 bg-gray-200 rounded-lg mb-4 overflow-hidden">
                        <!-- Placeholder Foto Dokter -->
                        <img src="https://via.placeholder.com/300x400?text=Dokter" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-bold text-lg">{{ $doc->user->name }}</h3>
                    <p class="text-emerald-600 text-sm font-medium mb-3">{{ $doc->poli->name }}</p>
                    <a href="{{ route('appointment.create', ['doctor_id' => $doc->id]) }}" class="block w-full bg-emerald-100 text-emerald-700 text-center py-2 rounded-lg font-bold hover:bg-emerald-200">
                        Buat Janji
                    </a>
                </div>
                @endforeach
            </div>
            
             <div class="text-center mt-8">
                <a href="{{ route('public.doctors') }}" class="text-emerald-600 font-bold hover:underline">Lihat Jadwal Dokter Lengkap &rarr;</a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8 text-center">
        <p>&copy; 2025 Rumah Sakit Sehat Sejahtera. All rights reserved.</p>
    </footer>

</body>
</html>