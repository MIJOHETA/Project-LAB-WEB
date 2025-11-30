<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - RS Sehat Sejahtera</title>
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
            <img src="{{ asset('images/login.png') }}" 
                 alt="Keluarga Bahagia" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-gradient-to-r from-black/10 to-transparent"></div>
        </div>

        <div class="w-full lg:w-[35%] flex flex-col justify-center px-8 py-12 lg:px-12 bg-white relative">
            
            <div class="w-full max-w-md mx-auto">
                <div class="text-center mb-8">
                    <div class="flex justify-center items-center flex-col">
                        <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-16 w-auto mb-3">
                        <h2 class="text-emerald-800 font-bold text-lg uppercase tracking-wider leading-none">Rumah Sakit</h2>
                        <h2 class="text-emerald-600 font-bold text-lg uppercase tracking-wider leading-none">Sehat Sejahtera</h2>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-emerald-900">Selamat Datang Kembali</h1>
                    <p class="text-gray-500 text-sm mt-1">Masuk ke akun Anda untuk booking janji temu</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input id="email" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-3 px-4" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               placeholder="contoh@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                        <input id="password" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-3 px-4" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="********">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                            <span class="ms-2 text-gray-600">Ingat Saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-gray-500 hover:text-emerald-600 transition" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-slate-800 text-white font-bold py-3.5 px-4 rounded-lg hover:bg-slate-900 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 uppercase tracking-wide">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-gray-600">
                    <span class="mr-1">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="text-emerald-700 font-bold hover:underline">
                        Daftar Pasien Baru
                    </a>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-gray-400 text-xs hover:text-gray-600">
                        &larr; Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>