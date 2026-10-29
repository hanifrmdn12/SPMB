<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'nomor_pendaftaran', 'access_pin', 'otp_kode', 'otp_kadaluarsa_pada', 'otp_percobaan', 'otp_dikirim_pada', 'calon_siswa_id', 'orang_tua_id', 'tahun_ajaran', 'gelombang',
        'tanggal_pendaftaran', 'status',
    ];

    protected function casts(): array
    {
        return ['tanggal_pendaftaran' => 'date', 'otp_kadaluarsa_pada' => 'datetime', 'otp_dikirim_pada' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'nomor_pendaftaran';
    }

    public function calonSiswa(): BelongsTo
    {
        return $this->belongsTo(CalonSiswa::class);
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(DokumenPersyaratan::class);
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(StatusPendaftaran::class);
    }
}
