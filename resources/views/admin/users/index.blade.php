<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Pengguna') }}
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
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-user-modal')"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-bold">
                    + Tambah Pengguna Baru
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Dokter</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $user->role === 'dokter' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $user->role === 'pasien' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($user->role === 'dokter' && $user->doctor)
                                        SIP: {{ $user->doctor->sip }} <br>
                                        Poli: {{ $user->doctor->poli->name ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-user-modal" focusable>
        <div class="p-6" x-data="{ role: 'pasien' }">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tambah Pengguna Baru</h2>
            
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="mb-4">
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="password" value="Password Default" />
                    <x-text-input id="password" name="password" type="text" value="password" class="mt-1 block w-full" required />
                </div>

                <div class="mb-4">
                    <x-input-label for="role" value="Peran / Role" />
                    <select id="role" name="role" x-model="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="pasien">Pasien</option>
                        <option value="dokter">Dokter</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div x-show="role === 'dokter'" class="bg-gray-50 p-4 rounded-lg mb-4 border">
                    <h3 class="font-bold text-gray-700 mb-2">Data Tambahan Dokter</h3>
                    
                    <div class="mb-3">
                        <x-input-label for="poli_id" value="Pilih Poli" />
                        <select name="poli_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">-- Pilih Poli --</option>
                            @foreach($polis as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-input-label for="sip" value="Nomor SIP" />
                        <x-text-input name="sip" type="text" class="mt-1 block w-full" placeholder="Contoh: 123/SIP/2025" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-input-label for="phone" value="No. HP" />
                    <x-text-input name="phone" type="text" class="mt-1 block w-full" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button class="ml-3">Simpan User</x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>