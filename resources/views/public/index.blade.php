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
        .hero-section {
            position: relative;
            overflow: hidden;
        }

        /* CSS untuk Animasi Marquee (Komedi Putar Logo) */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: flex;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
        }
        /* Pause animasi saat kursor diarahkan */
        .group:hover .animate-marquee {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20 md:grid md:grid-cols-3">
                <div class="flex items-center md:justify-self-start">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS Sehat Sejahtera" class="h-14 w-auto">
                    </a>
                </div>

                <div class="hidden md:flex items-center justify-center space-x-8 font-medium text-gray-600 md:justify-self-center">
                    <a href="{{ route('home') }}" class="text-emerald-600 font-bold">Beranda</a>
                    <a href="{{ route('public.doctors') }}" class="hover:text-emerald-600 transition">Cari Dokter</a>
                    <a href="{{ route('public.polis') }}" class="hover:text-emerald-600 transition">Layanan Poli</a>
                </div>

                <div class="flex items-center space-x-3 md:justify-self-end">
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

    <header class="hero-section relative bg-white pt-24 pb-32">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/sari-asih.png') }}" alt="Background RS" class="w-full h-full object-cover object-center opacity-20">
        </div>
        <div class="container relative z-10 mx-auto px-4 flex flex-col items-center text-center md:text-left md:items-start">
            <div class="w-full md:w-3/4 lg:w-1/2"> 
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                    Solusi Kesehatan <br> <span class="text-emerald-600">Terpercaya</span> Keluarga
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed max-w-lg md:pr-0">
                    Kami menghadirkan pelayanan medis terbaik dengan dokter spesialis berpengalaman dan fasilitas modern untuk kesehatan Anda.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 justify-center md:justify-start">
                    <a href="{{ route('public.doctors') }}" class="bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-emerald-700 transition shadow-xl hover:shadow-2xl hover:-translate-y-1 transform duration-300 text-center">
                        Buat Janji Temu
                    </a>
                    <a href="#poli" class="bg-white border-2 border-gray-100 text-gray-600 px-8 py-4 rounded-xl font-bold hover:border-emerald-600 hover:text-emerald-600 transition text-center">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section id="poli" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-emerald-600 font-bold tracking-wider uppercase text-xs">Kategori Layanan</span>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Pilih Spesialisasi</h2>
                <p class="text-gray-500 mt-2">Temukan dokter spesialis sesuai keluhan kesehatan Anda</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($polis as $poli)
                <a href="{{ route('public.doctors', ['poli_id' => $poli->id]) }}" class="group block h-full">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col items-center justify-center min-h-[200px] transition-all duration-300 hover:shadow-lg hover:border-emerald-200 hover:-translate-y-2">
                        <div class="mb-4 flex items-center justify-center h-24 w-full bg-white rounded-xl">
                            @if($poli->image)
                                <img src="{{ asset('storage/' . $poli->image) }}" class="h-full w-auto object-contain transition-transform duration-300 group-hover:scale-110">
                            @else
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

    <section class="py-24 bg-white overflow-hidden" id="about-section">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 relative z-10">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="h-8 w-1 bg-emerald-600 rounded-full"></div>
                        <span class="text-emerald-600 font-bold tracking-wider uppercase text-sm">Sekilas Tentang</span>
                    </div>
                    <h2 class="text-3xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Rumah Sakit <br> <span class="text-emerald-600">Sehat Sejahtera</span>
                    </h2>
                    <p class="text-gray-500 text-lg leading-relaxed mb-8">
                        Sebagai penyedia layanan kesehatan terpercaya, kami memiliki motto 
                        <span class="font-bold text-gray-800">"Melayani dengan Sepenuh Hati"</span>. 
                        Kami senantiasa memberikan mutu pelayanan profesional dengan fasilitas modern 
                        kepada seluruh pasien kami demi tercapainya kualitas hidup yang lebih baik.
                    </p>
                    <div class="grid grid-cols-3 gap-8 border-t border-gray-100 pt-8">
                        <div>
                            <span class="block text-4xl font-bold text-emerald-600 mb-1"><span class="counter" data-target="12">0</span></span>
                            <span class="text-sm text-gray-500 font-medium">Layanan Poli</span>
                        </div>
                        <div>
                            <span class="block text-4xl font-bold text-emerald-600 mb-1"><span class="counter" data-target="24">0</span></span>
                            <span class="text-sm text-gray-500 font-medium">Dokter Spesialis</span>
                        </div>
                        <div>
                            <span class="block text-4xl font-bold text-emerald-600 mb-1"><span class="counter" data-target="11">0</span></span>
                            <span class="text-sm text-gray-500 font-medium">Mitra Asuransi</span>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 relative mt-12 lg:mt-0">
                    <div class="relative w-full max-w-lg mx-auto lg:mr-0">
                        <div class="ml-12 relative z-0">
                            <img src="{{ asset('images/tentang1.png') }}" alt="Fasilitas RS" class="rounded-3xl shadow-xl w-full object-cover h-80 lg:h-96">
                        </div>
                        <div class="absolute -bottom-12 left-0 w-3/5 z-10">
                            <img src="{{ asset('images/tentang2.png') }}" alt="Pelayanan RS" class="rounded-3xl shadow-2xl border-8 border-white w-full object-cover h-48 lg:h-64">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
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
                        <div class="text-xs text-gray-600 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100 flex-1">
                            @if($doc->schedules->count() > 0)
                                <div class="space-y-1 max-h-24 overflow-y-auto scrollbar-thin">
                                    @foreach($doc->schedules as $sch)
                                        <div class="flex justify-between border-b border-gray-200 last:border-0 pb-1 last:pb-0">
                                            <span class="font-semibold text-gray-700">{{ $sch->day }}</span>
                                            <span>{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="italic text-red-400">Jadwal belum tersedia</span>
                            @endif
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('pasien.appointment.create', ['doctor_id' => $doc->id]) }}" class="block w-full bg-white border-2 border-emerald-600 text-emerald-600 text-center py-2.5 rounded-lg font-bold group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                                Buat Janji
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8 md:hidden">
                <a href="{{ route('public.doctors') }}" class="text-emerald-600 font-bold">Lihat Semua Dokter &rarr;</a>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white overflow-hidden group">
        <div class="container mx-auto px-4 mb-10 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Mitra Asuransi & Rekanan</h2>
            <p class="text-gray-500 mt-2">Kami bekerja sama dengan berbagai asuransi terkemuka</p>
        </div>
        
        <div class="relative w-full overflow-hidden">
            <div class="animate-marquee flex items-center">
                
                <div class="flex items-center space-x-12 mx-6 shrink-0">
                    <img src="{{ asset('images/aia.png') }}" alt="AIA" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/allianz.png') }}" alt="Allianz" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/astra-live.png') }}" alt="Astra Life" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/axa.png') }}" alt="AXA Mandiri" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/bpjs.png') }}" alt="BPJS Kesehatan" class="h-14 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/bri.png') }}" alt="BRI Life" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/great.png') }}" alt="Great Eastern" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/manulife.png') }}" alt="Manulife" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/prudential.png') }}" alt="Prudential" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/sinarmas.png') }}" alt="Sinarmas" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/bni.png') }}" alt="BNI Life" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                </div>

                <div class="flex items-center space-x-12 mx-6 shrink-0" aria-hidden="true">
                    <img src="{{ asset('images/aia.png') }}" alt="AIA" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/allianz.png') }}" alt="Allianz" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/astra-live.png') }}" alt="Astra Life" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/axa.png') }}" alt="AXA Mandiri" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/bpjs.png') }}" alt="BPJS Kesehatan" class="h-14 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/bri.png') }}" alt="BRI Life" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/great.png') }}" alt="Great Eastern" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/manulife.png') }}" alt="Manulife" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/prudential.png') }}" alt="Prudential" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/sinarmas.png') }}" alt="Sinarmas" class="h-10 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                    <img src="{{ asset('images/bni.png') }}" alt="BNI Life" class="h-12 w-auto object-contain grayscale hover:grayscale-0 transition duration-300">
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-emerald-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-10 md:gap-20">
                
                <div class="flex items-center space-x-6">
                    <div class="bg-white p-2 rounded-xl shrink-0">
                         <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS Sehat Sejahtera" class="h-20 w-auto">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold tracking-wider leading-none">RUMAH SAKIT</h3>
                        <h4 class="text-3xl font-extrabold text-emerald-400 leading-none mt-1">SEHAT SEJAHTERA</h4>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h5 class="text-xl font-semibold mb-6 text-emerald-300 uppercase tracking-wider">Call Center</h5>
                    <div class="space-y-4">
                        <p class="flex items-center justify-center md:justify-start text-2xl font-bold">
                            <i class="fas fa-phone-volume mr-4 text-emerald-400"></i> (021) 552-2794
                        </p>
                        <p class="flex items-center justify-center md:justify-start text-lg font-medium text-emerald-100">
                            <i class="fas fa-envelope mr-4 text-emerald-400"></i> info@rssehatsejahtera.id
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t border-emerald-800 mt-14 pt-8 text-center text-sm text-emerald-300">
                &copy; 2025 Rumah Sakit Sehat Sejahtera. Melayani dengan hati, merawat dengan teknologi terkini.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll('.counter');
            const animateCounters = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = +counter.getAttribute('data-target');
                        const speed = 200; 
                        const updateCount = () => {
                            const count = +counter.innerText;
                            const increment = target / speed;
                            if (count < target) {
                                counter.innerText = Math.ceil(count + increment);
                                setTimeout(updateCount, 20);
                            } else {
                                counter.innerText = target;
                            }
                        };
                        updateCount();
                        observer.unobserve(counter); 
                    }
                });
            };
            const observer = new IntersectionObserver(animateCounters, { threshold: 0.5 });
            counters.forEach(counter => { observer.observe(counter); });
        });
    </script>

</body>
</html>