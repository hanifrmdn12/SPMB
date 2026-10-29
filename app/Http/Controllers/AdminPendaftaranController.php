<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPendaftaranController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pendaftaran::query()->with('calonSiswa')->latest('tanggal_pendaftaran')->latest('id');

        $query->when($request->filled('tahun_ajaran'), fn ($builder) => $builder->where('tahun_ajaran', $request->string('tahun_ajaran')))
            ->when($request->filled('gelombang'), fn ($builder) => $builder->where('gelombang', $request->string('gelombang')))
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')))
            ->when($request->filled('kata_kunci'), function ($builder) use ($request): void {
                $kataKunci = $request->string('kata_kunci');
                $builder->where(function ($filter) use ($kataKunci): void {
                    $filter->where('nomor_pendaftaran', 'like', "%{$kataKunci}%")
                        ->orWhereHas('calonSiswa', fn ($calonSiswa) => $calonSiswa->where('nama_lengkap', 'like', "%{$kataKunci}%"));
                });
            });

        return view('admin.pendaftaran.index', [
            'pendaftaran' => $query->paginate(10)->withQueryString(),
            'tahunAjaran' => Pendaftaran::query()->select('tahun_ajaran')->distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran'),
            'gelombang' => Pendaftaran::query()->whereNotNull('gelombang')->select('gelombang')->distinct()->orderBy('gelombang')->pluck('gelombang'),
            'statusPilihan' => ['menunggu_dokumen', 'perlu_perbaikan_dokumen', 'menunggu_pembayaran', 'menunggu_verifikasi_pembayaran', 'perlu_perbaikan_pembayaran', 'siap_keputusan', 'diterima', 'ditolak'],
        ]);
    }
}
