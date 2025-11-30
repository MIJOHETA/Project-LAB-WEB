<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Profil Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-emerald-100 text-emerald-700 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('dokter.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center space-x-6 mb-6">
                        <div class="shrink-0">
                            @if($doctor->photo)
                                <img class="h-24 w-24 object-cover rounded-full border-2 border-emerald-500" src="{{ asset('storage/' . $doctor->photo) }}" alt="Foto Dokter">
                            @else
                                <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                            @endif
                        </div>
                        <label class="block">
                            <span class="sr-only">Pilih Foto Profil</span>
                            <input type="file" name="photo" class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-emerald-50 file:text-emerald-700
                              hover:file:bg-emerald-100
                            "/>
                            @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nomor HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-300 rounded shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nomor SIP (Hubungi Admin untuk ubah)</label>
                        <input type="text" value="{{ $doctor->sip }}" class="w-full border-gray-300 rounded shadow-sm bg-gray-100 text-gray-500" disabled>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded font-bold hover:bg-emerald-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>