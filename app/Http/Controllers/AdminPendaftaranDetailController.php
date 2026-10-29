<?php

namespace App\Http\Controllers;

use App\Models\DokumenPersyaratan;
use App\Models\JenisDokumen;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\StatusPendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminPendaftaranDetailController extends Controller
{
    public function show(Pendaftaran $pendaftaran): View
    {
        $pendaftaran->load([
            'calonSiswa', 'orangTua', 'dokumen.jenisDokumen',
            'pembayaran' => fn ($query) => $query->latest(),
            'riwayatStatus' => fn ($query) => $query->latest('dibuat_pada'),
        ]);

        return view('admin.pendaftaran.detail', compact('pendaftaran'));
    }

    public function verifikasiDokumen(Request $request, Pendaftaran $pendaftaran, DokumenPersyaratan $dokumen): RedirectResponse
    {
        abort_unless($dokumen->pendaftaran_id === $pendaftaran->id, 404);
        $data = $request->validate(['status_verifikasi' => ['required', 'in:menunggu,valid,tidak_valid'], 'catatan' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($request, $pendaftaran, $dokumen, $data): void {
            $dokumen->update(['status_verifikasi' => $data['status_verifikasi'], 'catatan_verifikasi' => $data['catatan'] ?? null, 'diverifikasi_oleh' => $request->user()->admin?->id, 'diverifikasi_pada' => now()]);

            $dokumenWajib = JenisDokumen::query()->where('aktif', true)->where('wajib', true)->pluck('id');
            $dokumenPendaftaran = $pendaftaran->dokumen()->whereIn('jenis_dokumen_id', $dokumenWajib);
            $status = (clone $dokumenPendaftaran)->where('status_verifikasi', 'tidak_valid')->exists()
                ? 'perlu_perbaikan_dokumen'
                : ((clone $dokumenPendaftaran)->where('status_verifikasi', 'valid')->count() === $dokumenWajib->count()
                    ? 'menunggu_pembayaran'
                    : 'menunggu_dokumen');

            $this->perbaruiStatus($pendaftaran, $status, $status === 'menunggu_pembayaran'
                ? 'Seluruh dokumen wajib telah dinyatakan valid. Silakan lanjutkan pembayaran.'
                : ($status === 'perlu_perbaikan_dokumen' ? 'Ada dokumen yang perlu diperbaiki.' : 'Dokumen sedang dalam proses verifikasi.'), $request->user()->admin?->id);
        });

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }

    public function verifikasiPembayaran(Request $request, Pendaftaran $pendaftaran, Pembayaran $pembayaran): RedirectResponse
    {
        abort_unless($pembayaran->pendaftaran_id === $pendaftaran->id, 404);
        $data = $request->validate(['status_verifikasi' => ['required', 'in:menunggu,valid,tidak_valid'], 'catatan' => ['nullable', 'string', 'max:1000']]);
        DB::transaction(function () use ($request, $pendaftaran, $pembayaran, $data): void {
            $pembayaran->update(['status_verifikasi' => $data['status_verifikasi'], 'catatan_verifikasi' => $data['catatan'] ?? null, 'diverifikasi_oleh' => $request->user()->admin?->id, 'diverifikasi_pada' => now()]);
            $status = $data['status_verifikasi'] === 'valid' ? 'siap_keputusan' : 'perlu_perbaikan_pembayaran';
            $this->perbaruiStatus($pendaftaran, $status, $status === 'siap_keputusan'
                ? 'Pembayaran telah dinyatakan valid dan pendaftaran siap untuk diputuskan.'
                : 'Bukti pembayaran perlu diperbaiki atau diunggah ulang.', $request->user()->admin?->id);
        });

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function keputusan(Request $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:diterima,ditolak'], 'catatan' => ['nullable', 'string', 'max:1000']]);
        if ($data['status'] === 'diterima' && $pendaftaran->status !== 'siap_keputusan') {
            return back()->withErrors(['status' => 'Pendaftaran hanya dapat diterima setelah pembayaran dinyatakan valid.']);
        }
        DB::transaction(function () use ($request, $pendaftaran, $data): void {
            $this->perbaruiStatus($pendaftaran, $data['status'], $data['catatan'] ?? null, $request->user()->admin?->id);
        });

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function fileDokumen(Pendaftaran $pendaftaran, DokumenPersyaratan $dokumen)
    {
        abort_unless($dokumen->pendaftaran_id === $pendaftaran->id && Storage::disk('local')->exists($dokumen->path_file), 404);
        return Storage::disk('local')->download($dokumen->path_file, $dokumen->nama_file);
    }

    public function filePembayaran(Pendaftaran $pendaftaran, Pembayaran $pembayaran)
    {
        abort_unless($pembayaran->pendaftaran_id === $pendaftaran->id && Storage::disk('local')->exists($pembayaran->bukti_pembayaran), 404);
        return Storage::disk('local')->download($pembayaran->bukti_pembayaran, 'bukti-pembayaran-'.$pendaftaran->nomor_pendaftaran);
    }

    private function perbaruiStatus(Pendaftaran $pendaftaran, string $status, ?string $catatan, ?int $adminId): void
    {
        $pendaftaran->update(['status' => $status]);
        StatusPendaftaran::create(['pendaftaran_id' => $pendaftaran->id, 'status' => $status, 'catatan' => $catatan, 'admin_id' => $adminId]);
    }
}
