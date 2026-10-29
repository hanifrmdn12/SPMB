<?php

namespace Database\Seeders;

use App\Models\JenisDokumen;
use Illuminate\Database\Seeder;

class JenisDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            ['nama' => 'Akta Kelahiran', 'wajib' => true],
            ['nama' => 'Kartu Keluarga', 'wajib' => true],
            ['nama' => 'Pas Foto', 'wajib' => true],
            ['nama' => 'Kartu Identitas Orang Tua/Wali', 'wajib' => true],
        ] as $dokumen) {
            JenisDokumen::updateOrCreate(
                ['nama' => $dokumen['nama']],
                [
                    ...$dokumen,
                    'ukuran_maksimal_kb' => 2048,
                    'format_diizinkan' => 'pdf,jpg,jpeg,png',
                    'aktif' => true,
                ],
            );
        }
    }
}
