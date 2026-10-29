<?php

namespace App\Http\Controllers;

use App\Models\DokumenPersyaratan;
use App\Models\JenisDokumen;
use App\Models\Pendaftaran;
use App\Models\StatusPendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminVerifikasiDokumenController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->has('status') ? $request->input('status') : 'menunggu';
        $query = Pendaftaran::query()
            ->with(['calonSiswa', 'dokumen.jenisDokumen'])
            ->latest('tanggal_pendaftaran')
            ->latest('id');

        $query->when($request->filled('tahun_ajaran'), fn ($builder) => $builder->where('tahun_ajaran', $request->string('tahun_ajaran')))
            ->when($status !== '', fn ($builder) => $builder->whereHas('dokumen', fn ($dokumen) => $dokumen->where('status_verifikasi', $status)))
            ->when($request->filled('cari'), function ($builder) use ($request): void {
                $cari = $request->string('cari');
                $builder->where(function ($filter) use ($cari): void {
                    $filter->where('nomor_pendaftaran', 'like', "%{$cari}%")
                        ->orWhereHas('calonSiswa', fn ($calonSiswa) => $calonSiswa->where('nama_lengkap', 'like', "%{$cari}%"));
                });
            });

        $pendaftaran = $query->paginate(10)->withQueryString();
        $dipilih = $request->filled('pendaftaran')
            ? Pendaftaran::query()->with(['calonSiswa', 'dokumen.jenisDokumen'])->find($request->integer('pendaftaran'))
            : $pendaftaran->first();

        return view('admin.verifikasi-dokumen.index', [
            'pendaftaran' => $pendaftaran,
            'dipilih' => $dipilih,
            'tahunAjaran' => Pendaftaran::query()->select('tahun_ajaran')->distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran'),
        ]);
    }

    public function update(Request $request, Pendaftaran $pendaftaran, DokumenPersyaratan $dokumen): RedirectResponse
    {
        abort_unless($dokumen->pendaftaran_id === $pendaftaran->id, 404);

        $data = $request->validate([
            'status_verifikasi' => ['required', 'in:valid,tidak_valid'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $pendaftaran, $dokumen, $data): void {
            $dokumen->update([
                'status_verifikasi' => $data['status_verifikasi'],
                'catatan_verifikasi' => $data['catatan'] ?? null,
                'diverifikasi_oleh' => $request->user()->admin?->id,
                'diverifikasi_pada' => now(),
            ]);

            $dokumenWajib = JenisDokumen::query()->where('aktif', true)->where('wajib', true)->pluck('id');
            $dokumenPendaftaran = $pendaftaran->dokumen()->whereIn('jenis_dokumen_id', $dokumenWajib);
            $status = (clone $dokumenPendaftaran)->where('status_verifikasi', 'tidak_valid')->exists()
                ? 'perlu_perbaikan_dokumen'
                : ((clone $dokumenPendaftaran)->where('status_verifikasi', 'valid')->count() === $dokumenWajib->count()
                    ? 'menunggu_pembayaran'
                    : 'menunggu_dokumen');

            $pendaftaran->update(['status' => $status]);
            StatusPendaftaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'status' => $status,
                'catatan' => $status === 'menunggu_pembayaran'
                    ? 'Seluruh dokumen wajib telah dinyatakan valid. Silakan lanjutkan pembayaran.'
                    : ($status === 'perlu_perbaikan_dokumen' ? 'Ada dokumen yang perlu diperbaiki.' : 'Dokumen sedang dalam proses verifikasi.'),
                'admin_id' => $request->user()->admin?->id,
            ]);
        });

        return to_route('admin.verifikasi-dokumen.index', ['pendaftaran' => $pendaftaran->id])
            ->with('success', 'Verifikasi dokumen berhasil disimpan.');
    }
}
