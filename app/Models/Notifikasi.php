<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = ['penerima_pengguna_id', 'judul', 'pesan', 'dibaca', 'waktu_kirim'];

    protected $casts = [
        'dibaca' => 'boolean',
        'waktu_kirim' => 'datetime',
    ];

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'penerima_pengguna_id');
    }

    public function pembaca()
    {
        return $this->belongsToMany(Pengguna::class, 'notifikasi_dibaca', 'notifikasi_id', 'pengguna_id')->withTimestamps();
    }
}
