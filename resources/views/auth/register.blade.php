<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien Baru - RS Sehat Sejahtera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen">
        
        <div class="hidden lg:block lg:w-[65%] relative">
            <img src="{{ asset('images/register.png') }}" 
                 alt="Keluarga Bahagia" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-black/5"></div>
        </div>

        <div class="w-full lg:w-[35%] flex flex-col justify-center px-8 py-12 lg:px-10 bg-white relative overflow-y-auto">
            
            <div class="w-full max-w-md mx-auto">
                <div class="text-center mb-6">
                    <div class="flex justify-center items-center flex-col">
                        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-16 w-auto mb-2">
                        <h2 class="text-emerald-800 font-bold text-sm uppercase tracking-wider leading-tight">Rumah Sakit <br> Sehat Sejahtera</h2>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-xl font-bold text-emerald-800">Pendaftaran Pasien Baru</h1>
                    <p class="text-gray-500 text-xs mt-1">Buat akun untuk booking janji temu tanpa antri</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="name" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2.5 px-4 text-sm" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               placeholder="Masukkan nama lengkap Anda">
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input id="email" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2.5 px-4 text-sm" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username"
                               placeholder="contoh@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                        <input id="password" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2.5 px-4 text-sm" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input id="password_confirmation" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-2.5 px-4 text-sm" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Ulangi password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    <input type="hidden" name="role" value="pasien">

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 underline hover:text-emerald-700">
                            Sudah terdaftar?
                        </a>

                        <button type="submit" class="bg-slate-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-slate-900 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 text-sm tracking-wide uppercase">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="text-gray-400 text-xs hover:text-gray-600">
                        &larr; Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>