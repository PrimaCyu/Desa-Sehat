<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Anggota Keluarga Table
        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->string('kategori')->index(); // 'ibu_hamil', 'bayi', 'balita', 'anak', 'remaja', 'dewasa', 'lansia'
            $table->timestamps();
        });

        // 2. Catatan Kesehatan Table (Consolidated Exams & Visits)
        Schema::create('catatan_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_keluarga_id')->constrained('anggota_keluarga')->onDelete('cascade');
            $table->string('kategori')->index(); // e.g. 'ibu_hamil', 'bayi', 'lansia', etc.
            $table->date('tanggal_periksa')->index();
            
            // Common parameters
            $table->decimal('berat_badan', 5, 2); // kg
            $table->decimal('tinggi_badan', 5, 2)->nullable(); // cm (bayi, balita, anak, dll)
            
            // Bumil specific
            $table->string('tekanan_darah')->nullable(); // e.g. "120/80" (bumil, lansia)
            $table->integer('usia_kehamilan')->nullable(); // minggu
            $table->decimal('tinggi_fundus', 4, 1)->nullable(); // cm
            
            // Bayi specific
            $table->decimal('lingkar_kepala', 4, 1)->nullable(); // cm
            
            // Lansia specific
            $table->integer('gula_darah')->nullable(); // mg/dL
            
            // Miscellaneous checkup results
            $table->text('keluhan')->nullable();
            $table->string('imunisasi')->nullable(); // (bayi, bumil)
            $table->string('vitamin')->nullable(); // (bayi, balita)
            $table->string('obat')->nullable(); // (lansia, dll)
            $table->text('catatan')->nullable(); // notes
            
            $table->timestamps();
        });

        // 3. Antrean Table (Daily Queues)
        Schema::create('antrean', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade'); // family account
            $table->date('tanggal_antrean')->index();
            $table->integer('nomor_antrean');
            $table->string('kode_antrean'); // e.g. "A-1"
            $table->enum('status', ['menunggu', 'dilayani', 'selesai', 'dilewati'])->default('menunggu')->index();
            $table->timestamp('dipanggil_at')->nullable();
            $table->timestamp('dilayani_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });

        // 4. Jadwal Table (Schedules)
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal_kegiatan')->index();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tempat');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 5. Pengumuman Table (Announcements)
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten');
            $table->timestamp('tanggal_terbit')->nullable();
            $table->foreignId('pembuat_pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Notifikasi Table (Notifications)
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerima_pengguna_id')->nullable()->constrained('pengguna')->onDelete('cascade'); // Null = broadcast
            $table->string('judul');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false)->index();
            $table->timestamps();
        });

        // 7. Log Audit Table (Audit Logs)
        Schema::create('log_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->string('aksi');
            $table->text('deskripsi');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_audit');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('antrean');
        Schema::dropIfExists('catatan_kesehatan');
        Schema::dropIfExists('anggota_keluarga');
    }
};
