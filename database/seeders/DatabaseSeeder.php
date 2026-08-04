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
            'name' => 'Kader Ibu Marni',
            'username' => 'kader',
            'email' => 'kader@desasehat.go.id',
            'password' => Hash::make('password'),
            'peran_id' => $kaderPeran->id,
            'nomor_telepon' => '081277778888',
        ]);

        

        
        
    }
}
