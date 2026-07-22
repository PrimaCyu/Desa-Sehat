<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class AnggotaKeluarga extends Model
{
    protected $table = 'anggota_keluarga';

    protected $fillable = ['pengguna_id', 'nik', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'kategori', 'status_verifikasi'];

    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_verifikasi', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_verifikasi', 'ditolak');
    }

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected $appends = [
        'umur',
        'kategori_formatted',
        'hubungan_keluarga',
        'tanggal_lahir_formatted'
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function catatanKesehatan(): HasMany
    {
        return $this->hasMany(CatatanKesehatan::class, 'anggota_keluarga_id')->orderBy('tanggal_periksa', 'desc');
    }

    // Helper: Age in Indonesian
    public function getUmurAttribute(): string
    {
        if (!$this->tanggal_lahir) return '-';
        
        $birth = Carbon::parse($this->tanggal_lahir);
        $now = Carbon::now();
        
        $years = (int) $birth->diffInYears($now);
        $months = (int) ($birth->diffInMonths($now) % 12);
        
        if ($years > 0) {
            return $years . ' Tahun' . ($months > 0 ? ' ' . $months . ' Bulan' : '');
        }
        
        return $months . ' Bulan';
    }

    // Helper: Age in months
    public function getUmurDalamBulanAttribute(): int
    {
        if (!$this->tanggal_lahir) return 0;
        return (int) Carbon::parse($this->tanggal_lahir)->diffInMonths(Carbon::now());
    }

    // Helper: Kategori name format
    public function getKategoriFormattedAttribute(): string
    {
        $kategoriList = [
            'ibu_hamil' => 'Ibu Hamil',
            'bayi' => 'Bayi (<12 Bulan)',
            'balita' => 'Balita (1-5 Tahun)',
            'anak' => 'Anak-Anak',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];

        return $kategoriList[$this->kategori] ?? ucfirst($this->kategori);
    }

    // Helper: Family relationship name
    public function getHubunganKeluargaAttribute(): string
    {
        if ($this->pengguna && $this->nama === $this->pengguna->kepala_keluarga) {
            return 'Kepala Keluarga';
        }
        
        if ($this->kategori === 'ibu_hamil') {
            return 'Istri (Ibu)';
        }
        
        if (in_array($this->kategori, ['bayi', 'balita', 'anak', 'remaja'])) {
            return 'Anak';
        }
        
        if ($this->kategori === 'lansia') {
            return $this->jenis_kelamin === 'L' ? 'Kakek' : 'Nenek';
        }
        
        return $this->jenis_kelamin === 'L' ? 'Suami' : 'Istri';
    }

    // Helper: Formatted birth date
    public function getTanggalLahirFormattedAttribute(): string
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->translatedFormat('d F Y') : '-';
    }
}
