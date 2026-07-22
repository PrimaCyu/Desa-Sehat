<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanKesehatan extends Model
{
    protected $table = 'catatan_kesehatan';

    protected $fillable = [
        'anggota_keluarga_id',
        'kategori',
        'tanggal_periksa',
        'berat_badan',
        'tinggi_badan',
        'tekanan_darah',
        'usia_kehamilan',
        'tinggi_fundus',
        'lingkar_kepala',
        'gula_darah',
        'keluhan',
        'imunisasi',
        'vitamin',
        'obat',
        'catatan',
    ];

    protected $casts = [
        'tanggal_periksa' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'tinggi_fundus' => 'decimal:1',
        'lingkar_kepala' => 'decimal:1',
    ];

    public function anggotaKeluarga(): BelongsTo
    {
        return $this->belongsTo(AnggotaKeluarga::class, 'anggota_keluarga_id');
    }
}
