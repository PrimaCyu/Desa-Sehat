<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = ['judul', 'konten', 'tanggal_terbit', 'pembuat_pengguna_id'];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pembuat_pengguna_id');
    }
}
