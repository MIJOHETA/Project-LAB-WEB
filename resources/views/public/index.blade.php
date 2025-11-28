<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RS Sehat Sejahtera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-2">
                    <div class="bg-emerald-600 text-white p-2 rounded-lg">
                        <i class="fas fa-hospital text-2xl"></i>
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
                        <a href="{{ route('dashboard') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-full font-semibold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                            Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-emerald-600 font-semibold px-4">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-emerald-600 text-white px-5 py-2.5 rounded-full font-semibold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-white pt-16 pb-24">
        <div class="container mx-auto px-4 flex flex-col-reverse lg:flex-row items-center">
            <div class="lg:w-1/2 mt-10 lg:mt-0">
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Solusi Kesehatan <br> <span class="text-emerald-600">Terpercaya</span> Keluarga
                </h1>
                <p class="text-lg text-gray-500 mb-8 max-w-lg leading-relaxed">
                    Kami menghadirkan pelayanan medis terbaik dengan dokter spesialis berpengalaman dan fasilitas modern untuk kesehatan Anda.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('public.doctors') }}" class="bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-emerald-700 transition shadow-xl hover:shadow-2xl hover:-translate-y-1 transform duration-300">
                        Buat Janji Temu
                    </a>
                    <a href="#poli" class="bg-white border-2 border-gray-100 text-gray-600 px-8 py-4 rounded-xl font-bold hover:border-emerald-600 hover:text-emerald-600 transition">
                        Lihat Layanan
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 flex justify-center relative">
                <div class="absolute top-0 right-10 w-72 h-72 bg-emerald-100 rounded-full blur-3xl opacity-50 mix-blend-multiply animate-blob"></div>
                <div class="absolute bottom-0 left-10 w-72 h-72 bg-blue-100 rounded-full blur-3xl opacity-50 mix-blend-multiply animate-blob animation-delay-2000"></div>
                <img src="https://img.freepik.com/free-vector/doctors-concept-illustration_114360-1515.jpg" alt="Ilustrasi Dokter" class="relative z-10 w-full max-w-md hover:scale-105 transition duration-500">
            </div>
        </div>
    </header>

    <!-- SECTION POLI (YANG DIUBAH) -->
    <section id="poli" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-emerald-600 font-bold tracking-wider uppercase text-xs">Kategori Layanan</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Pilih Spesialisasi</h2>
                <p class="text-gray-500 mt-2">Temukan dokter spesialis sesuai keluhan kesehatan Anda</p>
            </div>

            <!-- GRID CARD LAYOUT (6 KOLOM) -->
            <!-- lg:grid-cols-6: Agar 6 item bisa muat sejajar -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($polis as $poli)
                <a href="{{ route('public.doctors', ['poli_id' => $poli->id]) }}" class="group block h-full">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col items-center justify-center min-h-[200px] transition-all duration-300 hover:shadow-lg hover:border-emerald-200 hover:-translate-y-2">
                        
                        <!-- Gambar / Ikon di Tengah -->
                        <div class="mb-4 flex items-center justify-center h-24 w-full">
                            @if($poli->image)
                                <!-- Gambar Upload (Besar & Jelas) -->
                                <img src="{{ asset('storage/' . $poli->image) }}" class="h-full w-auto object-contain transition-transform duration-300 group-hover:scale-110">
                            @else
                                <!-- Ikon FontAwesome (Besar & Jelas) -->
                                @php
                                    $name = strtolower($poli->name);
                                    $icon = 'fa-stethoscope'; 
                                    $color = 'text-emerald-500';
                                    
                                    if (str_contains($name, 'gigi')) { $icon = 'fa-tooth'; $color = 'text-blue-400'; }
                                    elseif (str_contains($name, 'anak')) { $icon = 'fa-baby'; $color = 'text-pink-400'; }
                                    elseif (str_contains($name, 'jantung')) { $icon = 'fa-heart-pulse'; $color = 'text-red-500'; }
                                    elseif (str_contains($name, 'mata')) { $icon = 'fa-eye'; $color = 'text-indigo-500'; }
                                    elseif (str_contains($name, 'paru')) { $icon = 'fa-lungs'; $color = 'text-orange-400'; }
                                    elseif (str_contains($name, 'syaraf')) { $icon = 'fa-brain'; $color = 'text-purple-500'; }
                                    elseif (str_contains($name, 'tulang') || str_contains($name, 'orthopedi')) { $icon = 'fa-bone'; $color = 'text-slate-500'; }
                                    elseif (str_contains($name, 'tht')) { $icon = 'fa-ear-listen'; $color = 'text-amber-500'; }
                                    elseif (str_contains($name, 'kandungan') || str_contains($name, 'obgyn')) { $icon = 'fa-person-pregnant'; $color = 'text-rose-400'; }
                                @endphp
                                <i class="fas {{ $icon }} {{ $color }} text-6xl transition-transform duration-300 group-hover:scale-110"></i>
                            @endif
                        </div>

                        <!-- Nama Poli (Font Bold & Jelas) -->
                        <h4 class="text-gray-800 font-bold text-center group-hover:text-emerald-600 transition-colors">
                            {{ $poli->name }}
                        </h4>
                    </div>
                </a>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('public.polis') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-emerald-700 bg-white border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 transition shadow-sm">
                    Lihat Seluruh Layanan
                </a>
            </div>
        </div>
    </section>

    <!-- Dokter Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Dokter Kami</h2>
                    <p class="text-gray-500 mt-1">Siap melayani dengan sepenuh hati</p>
                </div>
                <a href="{{ route('public.doctors') }}" class="hidden md:block text-emerald-600 font-bold hover:text-emerald-700">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($doctors as $doc)
                <div class="group bg-white rounded-2xl border border-gray-100 p-4 hover:shadow-xl hover:-translate-y-1 transition duration-300">
                    <div class="h-56 bg-gray-100 rounded-xl mb-4 overflow-hidden relative">
                        <!-- Placeholder Foto Dokter -->
                        <div class="absolute inset-0 flex items-center justify-center text-gray-300 bg-gray-50">
                            <i class="fas fa-user-doctor text-6xl"></i>
                        </div>
                        <!-- Overlay Button -->
                        <div class="absolute inset-0 bg-emerald-600/0 group-hover:bg-emerald-600/20 transition-colors duration-300"></div>
                    </div>
                    <div class="text-center">
                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-emerald-600 transition">{{ $doc->user->name }}</h3>
                        <p class="text-emerald-500 text-sm font-medium mb-4">{{ $doc->poli->name }}</p>
                        
                        <a href="{{ route('pasien.appointment.create', ['doctor_id' => $doc->id]) }}" class="block w-full bg-white border-2 border-emerald-100 text-emerald-600 text-center py-2.5 rounded-lg font-bold group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 transition duration-300">
                            Buat Janji
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-8 md:hidden">
                <a href="{{ route('public.doctors') }}" class="text-emerald-600 font-bold">Lihat Semua Dokter &rarr;</a>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center items-center space-x-2 mb-6">
                <i class="fas fa-hospital text-emerald-500 text-3xl"></i>
                <span class="text-2xl font-bold">RS SEHAT SEJAHTERA</span>
            </div>
            <p class="text-slate-400 mb-8">Melayani dengan hati, merawat dengan teknologi terkini.</p>
            <div class="border-t border-slate-800 pt-8 text-sm text-slate-500">
                &copy; 2025 Rumah Sakit Sehat Sejahtera. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>