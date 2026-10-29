<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDaftarDiterimaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pendaftaran::query()
            ->with('calonSiswa')
            ->where('status', 'diterima')
            ->latest('updated_at')
            ->latest('id');

        $query->when($request->filled('tahun_ajaran'), fn ($builder) => $builder->where('tahun_ajaran', $request->string('tahun_ajaran')))
            ->when($request->filled('gelombang'), fn ($builder) => $builder->where('gelombang', $request->string('gelombang')))
            ->when($request->filled('cari'), function ($builder) use ($request): void {
                $cari = $request->string('cari');
                $builder->where(function ($filter) use ($cari): void {
                    $filter->where('nomor_pendaftaran', 'like', "%{$cari}%")
                        ->orWhereHas('calonSiswa', fn ($calonSiswa) => $calonSiswa->where('nama_lengkap', 'like', "%{$cari}%"));
                });
            });

        return view('admin.daftar-diterima.index', [
            'pendaftaran' => $query->paginate(10)->withQueryString(),
            'totalDiterima' => Pendaftaran::query()->where('status', 'diterima')->count(),
            'tahunAjaran' => Pendaftaran::query()->select('tahun_ajaran')->distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran'),
            'gelombang' => Pendaftaran::query()->whereNotNull('gelombang')->select('gelombang')->distinct()->orderBy('gelombang')->pluck('gelombang'),
        ]);
    }
}
