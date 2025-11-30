<x-app-layout>
    <x-slot name="header">
       <div class="flex justify-between items-center">
            
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-rs.png') }}" alt="Logo RS" class="h-12 w-auto">
                
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Ulasan Pelayanan') }}
                </h2>
            </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <div class="text-center mb-8">
                    <h3 class="text-lg font-bold text-gray-700">Bagaimana pengalaman Anda?</h3>
                    <p class="text-gray-500 text-sm">Konsultasi dengan <span class="font-semibold">Dr. {{ $appointment->doctor->user->name }}</span> ({{ $appointment->doctor->poli->name }})</p>
                    <p class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}</p>
                </div>

                <form action="{{ route('pasien.feedback.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <!-- Rating Bintang -->
                    <div class="mb-6 text-center">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating Kepuasan</label>
                        <div class="flex justify-center flex-row-reverse gap-2">
                            <!-- Teknik CSS sederhana untuk rating bintang -->
                            <style>
                                .rating-input:checked ~ label,
                                .rating-input:hover ~ label,
                                .rating-label:hover ~ label { color: #facc15; }
                            </style>
                            
                            @for($i=5; $i>=1; $i--)
                                <input type="radio" id="star{{$i}}" name="rating" value="{{$i}}" class="rating-input hidden peer" required />
                                <label for="star{{$i}}" class="rating-label cursor-pointer text-3xl text-gray-300 transition-colors peer-checked:text-yellow-400">★</label>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400 mt-2">Pilih 1 sampai 5 bintang</p>
                    </div>

                    <!-- Komentar -->
                    <div class="mb-6">
                        <x-input-label for="comment" value="Komentar / Masukan (Opsional)" />
                        <textarea name="comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm mt-1 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Pelayanan sangat ramah, penjelasan dokter jelas..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('pasien.dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-md font-bold hover:bg-emerald-700 transition">
                            Kirim Ulasan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>