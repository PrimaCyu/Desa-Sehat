<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class LogAudit extends Model
{
    protected $table = 'log_audit';

    protected $fillable = ['pengguna_id', 'aksi', 'deskripsi', 'ip_address', 'user_agent'];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    // Static helper
    public static function log($aksi, $deskripsi, $penggunaId = null)
    {
        return self::create([
            'pengguna_id' => $penggunaId ?? auth()->id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
