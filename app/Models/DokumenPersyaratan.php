<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPersyaratan extends Model
{
    protected $table = 'dokumen_persyaratan';

    protected $fillable = [
        'pendaftaran_id', 'jenis_dokumen_id', 'path_file', 'nama_file',
        'status_verifikasi', 'catatan_verifikasi', 'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return ['diverifikasi_pada' => 'datetime'];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function jenisDokumen(): BelongsTo
    {
        return $this->belongsTo(JenisDokumen::class);
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'diverifikasi_oleh');
    }
}
