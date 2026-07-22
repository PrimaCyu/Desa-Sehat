<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = ['judul', 'tanggal_kegiatan', 'jam_mulai', 'jam_selesai', 'tempat', 'deskripsi'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];
}
