<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    protected $fillable = ['user_id', 'nama', 'nomor_whatsapp'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dokumenDiverifikasi(): HasMany
    {
        return $this->hasMany(DokumenPersyaratan::class, 'diverifikasi_oleh');
    }

    public function pembayaranDiverifikasi(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'diverifikasi_oleh');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(StatusPendaftaran::class);
    }
}
