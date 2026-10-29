<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalonSiswa extends Model
{
    protected $table = 'calon_siswa';

    protected $fillable = [
        'nama_lengkap', 'nik', 'nisn', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'alamat',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
