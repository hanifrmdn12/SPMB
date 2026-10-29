<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusPendaftaran extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $table = 'status_pendaftaran';

    protected $fillable = ['pendaftaran_id', 'status', 'catatan', 'admin_id', 'dibuat_pada'];

    protected function casts(): array
    {
        return ['dibuat_pada' => 'datetime'];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
