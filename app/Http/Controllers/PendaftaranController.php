<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendaftaranRequest;
use App\Models\CalonSiswa;
use App\Models\OrangTua;
use App\Models\Pendaftaran;
use App\Models\StatusPendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function informasi(): View
    {
        return view('pendaftaran.informasi');
    }

    public function create(): View
    {
        return view('pendaftaran.formulir');
    }

    public function store(StorePendaftaranRequest $request): RedirectResponse
    {
        $pendaftaran = DB::transaction(function () use ($request): Pendaftaran {
            $calonSiswa = CalonSiswa::create($request->safe()->only([
                'nama_lengkap', 'nik', 'nisn', 'tempat_lahir', 'tanggal_lahir',
                'jenis_kelamin', 'agama', 'alamat',
            ]));

            $orangTua = OrangTua::create($request->safe()->only([
                'nama_wali', 'nomor_whatsapp', 'alamat',
            ]));

            $pendaftaran = Pendaftaran::create([
                'nomor_pendaftaran' => $this->nomorPendaftaran(),
                'calon_siswa_id' => $calonSiswa->id,
                'orang_tua_id' => $orangTua->id,
                'tahun_ajaran' => $request->validated('tahun_ajaran'),
                'tanggal_pendaftaran' => today(),
                'status' => 'menunggu_dokumen',
            ]);

            StatusPendaftaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'status' => 'menunggu_dokumen',
                'catatan' => 'Data pendaftaran berhasil dibuat. Silakan unggah dokumen persyaratan.',
            ]);

            return $pendaftaran;
        });

        $request->session()->put("pendaftaran_access.{$pendaftaran->id}", true);

        return to_route('pendaftaran.dokumen', $pendaftaran)
            ->with('success', 'Data diri berhasil disimpan. Lanjutkan dengan mengunggah dokumen persyaratan.');
    }

    private function nomorPendaftaran(): string
    {
        do {
            $nomor = 'SPMB-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Pendaftaran::where('nomor_pendaftaran', $nomor)->exists());

        return $nomor;
    }
}
