@extends('layouts.app')

@section('title', 'Catat Pemeriksaan Kesehatan')

@section('content')
<!-- Header Back Button -->
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('kader.families.show', $member->pengguna_id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3.5 py-2 rounded-xl transition shadow-xs">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Batal & Kembali ke Profil Keluarga
    </a>
    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full">
        Kategori: {{ $member->kategori_formatted }}
    </span>
</div>

<!-- Form Container Card -->
<div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-xs max-w-2xl mx-auto">
    <div class="flex items-center gap-3 border-b border-slate-150 pb-4 mb-6">
        <div class="bg-emerald-50 text-emerald-600 h-11 w-11 rounded-xl flex items-center justify-center font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 110 4v3a2 2 0 002 2h10a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2h-2z"></path></svg>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-800">Catat Hasil Pemeriksaan Kesehatan</h3>
            <p class="text-xs text-slate-400 mt-0.5">Nama: <span class="text-slate-700 font-semibold">{{ $member->nama }}</span> ({{ $member->umur }})</p>
        </div>
    </div>

    <!-- Checkup Form -->
    <form action="{{ route('kader.health.check.store', $member->id) }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Category: Ibu Hamil Form -->
            @if($member->kategori === 'ibu_hamil')
                <div class="col-span-2">
                    <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Formulir Ibu Hamil</h4>
                </div>
                <div>
                    <label for="weight" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Berat Badan (kg) *</label>
                    <input type="number" step="0.01" id="weight" name="weight" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="blood_pressure" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tekanan Darah (mmHg) *</label>
                    <input type="text" id="blood_pressure" name="blood_pressure" required placeholder="Contoh: 120/80" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="pregnancy_age_weeks" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Usia Kehamilan (Minggu) *</label>
                    <input type="number" id="pregnancy_age_weeks" name="pregnancy_age_weeks" required min="1" max="44" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="fundal_height" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tinggi Fundus (cm)</label>
                    <input type="number" step="0.1" id="fundal_height" name="fundal_height" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div class="col-span-2">
                    <label for="complaints" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keluhan Ibu Hamil</label>
                    <textarea id="complaints" name="complaints" rows="2" placeholder="Contoh: Mual, kaki bengkak..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition"></textarea>
                </div>
                <div>
                    <label for="immunizations" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Imunisasi TT Ke-</label>
                    <input type="text" id="immunizations" name="immunizations" placeholder="Contoh: TT 2" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="vitamins" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Vitamins / Tablet Fe Diberikan</label>
                    <input type="text" id="vitamins" name="vitamins" placeholder="Contoh: Tablet Fe 30 Butir" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>

            <!-- Category: Bayi / Balita Form -->
            @elseif(in_array($member->kategori, ['bayi', 'balita']))
                <div class="col-span-2">
                    <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Formulir Bayi & Balita</h4>
                </div>
                <div>
                    <label for="weight" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Berat Badan (kg) *</label>
                    <input type="number" step="0.01" id="weight" name="weight" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" placeholder="Berat badan anak">
                </div>
                <div>
                    <label for="height" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tinggi / Panjang Badan (cm) *</label>
                    <input type="number" step="0.01" id="height" name="height" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" placeholder="Tinggi badan anak">
                </div>
                <div>
                    <label for="head_circumference" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Lingkar Kepala (cm)</label>
                    <input type="number" step="0.1" id="head_circumference" name="head_circumference" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" placeholder="Lingkar kepala anak">
                </div>
                <div>
                    <label for="vitamins" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pemberian Vitamin A</label>
                    <select id="vitamins" name="vitamins" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition cursor-pointer">
                        <option value="">Tidak ada</option>
                        <option value="Kapsul Biru (Bayi)">Kapsul Biru (Bayi 6-11 Bulan)</option>
                        <option value="Kapsul Merah (Balita)">Kapsul Merah (Balita 12-59 Bulan)</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label for="immunizations" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Imunisasi Hari Ini</label>
                    <input type="text" id="immunizations" name="immunizations" placeholder="Contoh: DPT-HB-Hib 1, Campak..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>

            <!-- Category: Lansia Form -->
            @elseif($member->kategori === 'lansia')
                <div class="col-span-2">
                    <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Formulir Kesehatan Lansia</h4>
                </div>
                <div>
                    <label for="weight" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Berat Badan (kg) *</label>
                    <input type="number" step="0.01" id="weight" name="weight" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="blood_pressure" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tekanan Darah (mmHg) *</label>
                    <input type="text" id="blood_pressure" name="blood_pressure" required placeholder="Contoh: 140/90" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="blood_sugar" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kadar Gula Darah GDS (mg/dL)</label>
                    <input type="number" id="blood_sugar" name="blood_sugar" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition" placeholder="Cek gula darah acak">
                </div>
                <div>
                    <label for="medicines" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Obat / Vitamin Diberikan</label>
                    <input type="text" id="medicines" name="medicines" placeholder="Contoh: Amlodipine 5mg, Vitamin B" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div class="col-span-2">
                    <label for="complaints" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keluhan Lansia</label>
                    <textarea id="complaints" name="complaints" rows="2" placeholder="Contoh: Pusing, sendi nyeri..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition"></textarea>
                </div>

            <!-- Category: General (Remaja / Dewasa / Anak) -->
            @else
                <div class="col-span-2">
                    <h4 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Formulir Kesehatan Umum ({{ $member->kategori_formatted }})</h4>
                </div>
                <div>
                    <label for="weight" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Berat Badan (kg) *</label>
                    <input type="number" step="0.01" id="weight" name="weight" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div>
                    <label for="height" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tinggi Badan (cm)</label>
                    <input type="number" step="0.01" id="height" name="height" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div class="col-span-2">
                    <label for="blood_pressure" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tekanan Darah (mmHg)</label>
                    <input type="text" id="blood_pressure" name="blood_pressure" placeholder="Contoh: 120/80" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition">
                </div>
                <div class="col-span-2">
                    <label for="complaints" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keluhan / Catatan</label>
                    <textarea id="complaints" name="complaints" rows="2" placeholder="Catat keluhan jika ada..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition"></textarea>
                </div>
            @endif

            <!-- Notes -->
            <div class="col-span-2">
                <label for="notes" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Tambahan Kader</label>
                <textarea id="notes" name="notes" rows="2" placeholder="Tuliskan petunjuk kesehatan atau catatan lainnya..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-hidden transition"></textarea>
            </div>

        </div>

        <div class="pt-4 flex gap-3 border-t border-slate-100">
            <a href="{{ route('kader.families.show', $member->pengguna_id) }}" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl text-center transition">
                Batalkan
            </a>
            <button type="submit" class="flex-1 py-3 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition shadow-xs cursor-pointer">
                Simpan Rekam Medis
            </button>
        </div>

    </form>
</div>
@endsection
