<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-emerald-800">Pendaftaran Pasien Baru</h2>
        <p class="text-gray-500 text-sm">Buat akun untuk booking janji temu tanpa antri</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-emerald-800 font-semibold" />
            <x-text-input id="name" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-emerald-800 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-emerald-800 font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-emerald-800 font-semibold" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Hidden Role (Otomatis jadi Pasien) -->
        <input type="hidden" name="role" value="pasien">

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-emerald-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500" href="{{ route('login') }}">
                {{ __('Sudah terdaftar?') }}
            </a>

            <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>