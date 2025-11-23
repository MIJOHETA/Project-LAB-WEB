<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pemeriksaan Medis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info Pasien -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-bold text-blue-800 text-lg mb-2">Data Pasien</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Pasien</p>
                        <p class="font-semibold">{{ $appointment->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Keluhan</p>
                        <p class="font-semibold">{{ $appointment->complaint }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Pemeriksaan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('dokter.records.store', $appointment->id) }}" method="POST">
                    @csrf
                    
                    <!-- Diagnosa -->
                    <div class="mb-6">
                        <x-input-label value="Diagnosa Dokter" />
                        <textarea name="diagnosis" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required placeholder="Contoh: Infeksi Saluran Pernapasan Akut"></textarea>
                    </div>

                    <!-- Tindakan -->
                    <div class="mb-6">
                        <x-input-label value="Tindakan Medis / Saran" />
                        <textarea name="treatment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm mt-1" required placeholder="Contoh: Istirahat total 3 hari, banyak minum air putih"></textarea>
                    </div>

                    <hr class="my-6">

                    <!-- Resep Obat -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg text-gray-700">Resep Obat</h3>
                            <button type="button" onclick="addMedicineRow()" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
                                + Tambah Obat
                            </button>
                        </div>

                        <div id="medicine-container" class="space-y-3">
                            <!-- Row Obat Pertama (Default) -->
                            <div class="grid grid-cols-12 gap-2 medicine-row">
                                <div class="col-span-6">
                                    <select name="medicines[]" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach($medicines as $med)
                                            <option value="{{ $med->id }}">{{ $med->name }} (Stok: {{ $med->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="quantities[]" placeholder="Jml" class="w-full border-gray-300 rounded-md shadow-sm text-sm" min="1" value="1" required>
                                </div>
                                <div class="col-span-3">
                                    <input type="text" name="instructions[]" placeholder="Dosis (3x1)" class="w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                </div>
                                <div class="col-span-1 flex items-center">
                                    <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route('dokter.dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md mr-2 hover:bg-gray-400">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md font-bold hover:bg-blue-700" onclick="return confirm('Simpan hasil pemeriksaan? Data tidak bisa diubah lagi.')">
                            Simpan & Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script JavaScript Sederhana untuk Tambah Row Obat -->
    <script>
        function addMedicineRow() {
            const container = document.getElementById('medicine-container');
            const row = document.querySelector('.medicine-row').cloneNode(true);
            
            // Reset nilai input di row baru
            row.querySelector('select').value = '';
            row.querySelector('input[type="number"]').value = '1';
            row.querySelector('input[type="text"]').value = '';
            
            container.appendChild(row);
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.medicine-row');
            if (rows.length > 1) {
                btn.closest('.medicine-row').remove();
            } else {
                alert('Minimal satu obat harus diisi (atau kosongkan jika tanpa resep).');
            }
        }
    </script>
</x-app-layout>