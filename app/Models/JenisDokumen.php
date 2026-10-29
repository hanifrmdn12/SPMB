<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisDokumen extends Model
{
    protected $table = 'jenis_dokumen';

    protected $fillable = [
        'nama', 'wajib', 'ukuran_maksimal_kb', 'format_diizinkan', 'aktif',
    ];

    protected function casts(): array
    {
        return ['wajib' => 'boolean', 'aktif' => 'boolean'];
    }

    public function dokumenPersyaratan(): HasMany
    {
        return $this->hasMany(DokumenPersyaratan::class);
    }
}
