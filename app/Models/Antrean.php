<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrean extends Model
{
    protected $table = 'antrean';

    protected $fillable = [
        'pengguna_id',
        'tanggal_antrean',
        'nomor_antrean',
        'kode_antrean',
        'status',
        'dipanggil_at',
        'dilayani_at',
        'selesai_at',
    ];

    protected $casts = [
        'tanggal_antrean' => 'date',
        'dipanggil_at' => 'datetime',
        'dilayani_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Helper: translate status
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'menunggu' => 'Menunggu',
            'dilayani' => 'Sedang Dilayani',
            'selesai' => 'Selesai',
            'dilewati' => 'Dilewati',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    // Helper: badge colors
    public function getBadgeClassAttribute(): string
    {
        $classes = [
            'menunggu' => 'bg-amber-100 text-amber-800 border-amber-200',
            'dilayani' => 'bg-emerald-100 text-emerald-800 border-emerald-200 animate-pulse',
            'selesai' => 'bg-slate-100 text-slate-800 border-slate-200',
            'dilewati' => 'bg-rose-100 text-rose-800 border-rose-200',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
    }
}
