<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Poliklinik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex justify-end">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-poli-modal')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold">
                    + Tambah Poli
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($polis as $poli)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition">
                    @if($poli->image)
                        <img src="{{ asset('storage/' . $poli->image) }}" alt="{{ $poli->name }}" class="w-full h-32 object-cover">
                    @else
                        <div class="w-full h-32 bg-indigo-50 flex items-center justify-center text-indigo-300">
                            <span class="text-4xl"><i class="fas fa-clinic-medical"></i></span>
                        </div>
                    @endif
                    
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-gray-800">{{ $poli->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1 h-12 overflow-hidden">{{ $poli->description }}</p>
                        
                        <div class="mt-4 flex justify-between items-center pt-4 border-t">
                            <span class="text-xs text-gray-400">ID: {{ $poli->id }}</span>
                            <form action="{{ route('admin.polis.destroy', $poli->id) }}" method="POST" onsubmit="return confirm('Hapus poli ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-modal name="add-poli-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Poliklinik</h2>
            <form method="POST" action="{{ route('admin.polis.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <x-input-label value="Nama Poli" />
                    <x-text-input name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="mb-4">
                    <x-input-label value="Deskripsi" />
                    <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="mb-4">
                    <x-input-label value="Gambar (Opsional)" />
                    <input type="file" name="image" class="mt-1 block w-full border p-2 rounded">
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button class="ml-3">Simpan</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>