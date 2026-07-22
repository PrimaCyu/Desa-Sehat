<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengguna';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'peran_id',
        'kepala_keluarga',
        'alamat',
        'nomor_telepon',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function peran(): BelongsTo
    {
        return $this->belongsTo(Peran::class, 'peran_id');
    }

    public function anggotaKeluarga(): HasMany
    {
        return $this->hasMany(AnggotaKeluarga::class, 'pengguna_id');
    }

    public function antrean(): HasMany
    {
        return $this->hasMany(Antrean::class, 'pengguna_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'penerima_pengguna_id');
    }

    public function notifikasiDibaca()
    {
        return $this->belongsToMany(Notifikasi::class, 'notifikasi_dibaca', 'pengguna_id', 'notifikasi_id')->withTimestamps();
    }

    public function logAudit(): HasMany
    {
        return $this->hasMany(LogAudit::class, 'pengguna_id');
    }

    // Helper roles checking
    public function isKader(): bool
    {
        return $this->peran && $this->peran->nama === 'kader';
    }

    public function isWarga(): bool
    {
        return $this->peran && $this->peran->nama === 'warga';
    }
}
