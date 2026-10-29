<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pendaftaran_id', 'nominal', 'tanggal_pembayaran', 'bukti_pembayaran',
        'status_verifikasi', 'catatan_verifikasi', 'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'tanggal_pembayaran' => 'date',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'diverifikasi_oleh');
    }
}
