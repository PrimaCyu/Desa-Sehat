<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Peran (Roles)
        $kaderPeran = Peran::create([
            'nama' => 'kader',
            'display_peran' => 'Kader Posyandu',
            'deskripsi' => 'Petugas Posyandu yang mengelola seluruh pelayanan Posyandu',
        ]);

        $wargaPeran = Peran::create([
            'nama' => 'warga',
            'display_peran' => 'Keluarga Warga',
            'deskripsi' => 'Akun keluarga warga desa, login berbasis nomor KK',
        ]);

        // 2. Seed Pengguna (Akun-Akun Kader Posyandu)
        $kaderUser1 = Pengguna::create([
            'name' => 'Kader Ibu Marni (Utama)',
            'username' => 'kader',
            'email' => 'kader@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778888',
        ]);

        $kaderUser2 = Pengguna::create([
            'name' => 'Kader Ibu Siti (Administrasi)',
            'username' => 'kader_admin',
            'email' => 'kader_admin@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778889',
        ]);

        $kaderUser3 = Pengguna::create([
            'name' => 'Kader Ibu Sri (Antrean & Ukur)',
            'username' => 'kader_antrian',
            'email' => 'kader_antrian@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778890',
        ]);

        $kaderUser4 = Pengguna::create([
            'name' => 'Kader Bidan Dewi (Pelayanan 1)',
            'username' => 'kader_pelayanan1',
            'email' => 'kader_pelayanan1@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778891',
        ]);

        $kaderUser5 = Pengguna::create([
            'name' => 'Kader Bidan Ratna (Pelayanan 2)',
            'username' => 'kader_pelayanan2',
            'email' => 'kader_pelayanan2@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778892',
        ]);

        $kaderUser6 = Pengguna::create([
            'name' => 'Kader Ibu Nur (Penyuluhan & Gizi)',
            'username' => 'kader_penyuluhan',
            'email' => 'kader_penyuluhan@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778893',
        ]);

        // 3. Seed Warga Accounts (Login using username / No. KK)
        $warga1 = Pengguna::create([
            'name' => 'Keluarga Budi Utomo',
            'username' => '1234567890123456',
            'email' => 'budi@desasehat.test',
            'password' => Hash::make('password'),
            'peran_id' => $wargaPeran->id,
            'kepala_keluarga' => 'Budi Utomo',
            'alamat' => 'Dusun Maju Sehat RT 02 / RW 04, Desa Sehat',
            'nomor_telepon' => '081234567890',
        ]);

        $warga2 = Pengguna::create([
            'name' => 'Keluarga Slamet Riyadi',
            'username' => '6543210987654321',
            'email' => 'slamet@desasehat.test',
            'password' => Hash::make('password'),
            'peran_id' => $wargaPeran->id,
            'kepala_keluarga' => 'Slamet Riyadi',
            'alamat' => 'Dusun Maju Sehat RT 03 / RW 04, Desa Sehat',
            'nomor_telepon' => '081298765432',
        ]);

        // 4. Seed Anggota Keluarga under Warga accounts
        // Family 1 Members
        \App\Models\AnggotaKeluarga::create([
            'pengguna_id' => $warga1->id,
            'nik' => '3201010304050001',
            'nama' => 'Budi Utomo',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1992-06-15',
            'kategori' => 'dewasa',
        ]);

        \App\Models\AnggotaKeluarga::create([
            'pengguna_id' => $warga1->id,
            'nik' => '3201010304050002',
            'nama' => 'Siti Aminah',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '1995-09-20',
            'kategori' => 'ibu_hamil',
        ]);

        \App\Models\AnggotaKeluarga::create([
            'pengguna_id' => $warga1->id,
            'nik' => '3201010304050003',
            'nama' => 'Andi Utomo',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2023-03-10',
            'kategori' => 'balita',
        ]);

        \App\Models\AnggotaKeluarga::create([
            'pengguna_id' => $warga1->id,
            'nik' => '3201010304050004',
            'nama' => 'Mbah Joyo',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1958-01-01',
            'kategori' => 'lansia',
        ]);

        // Family 2 Members
        \App\Models\AnggotaKeluarga::create([
            'pengguna_id' => $warga2->id,
            'nik' => '3201010304050005',
            'nama' => 'Slamet Riyadi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-12-05',
            'kategori' => 'dewasa',
        ]);

        \App\Models\AnggotaKeluarga::create([
            'pengguna_id' => $warga2->id,
            'nik' => '3201010304050006',
            'nama' => 'Rina Riyadi',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2025-11-12',
            'kategori' => 'bayi',
        ]);
    }
}
