<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    protected $table = 'orang_tua';

    protected $fillable = [
        'user_id', 'nama_ayah', 'nama_ibu', 'nama_wali', 'nomor_whatsapp', 'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
