<x-app-layout>
    <x-slot name="header">
       <div class="flex justify-between items-center">
            
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-12 w-auto">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Obat-obatan') }}
                </h2>
            </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 flex justify-end">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-medicine-modal')"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 font-bold">
                    + Tambah Obat
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Obat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($medicines as $medicine)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $medicine->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($medicine->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($medicine->stock > 10)
                                        <span class="text-green-600 font-bold">{{ $medicine->stock }}</span>
                                    @elseif($medicine->stock > 0)
                                        <span class="text-orange-500 font-bold">{{ $medicine->stock }} (Menipis)</span>
                                    @else
                                        <span class="text-red-600 font-bold">Habis</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($medicine->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.medicines.destroy', $medicine->id) }}" method="POST" onsubmit="return confirm('Hapus obat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $medicines->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-medicine-modal" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Obat Baru</h2>
            <form method="POST" action="{{ route('admin.medicines.store') }}">
                @csrf
                <div class="mb-4">
                    <x-input-label value="Nama Obat" />
                    <x-text-input name="name" type="text" class="mt-1 block w-full" required />
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-input-label value="Tipe" />
                        <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="biasa">Biasa</option>
                            <option value="keras">Keras</option>
                            <option value="luar">Obat Luar</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Harga (Rp)" />
                        <x-text-input name="price" type="number" class="mt-1 block w-full" required />
                    </div>
                </div>
                <div class="mb-4">
                    <x-input-label value="Stok Awal" />
                    <x-text-input name="stock" type="number" class="mt-1 block w-full" required />
                </div>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button class="ml-3">Simpan</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>