<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\StatusPendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVerifikasiPembayaranController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->has('status') ? $request->input('status') : 'menunggu';
        $query = Pembayaran::query()
            ->with(['pendaftaran.calonSiswa'])
            ->latest('created_at');

        $query->when($request->filled('tahun_ajaran'), fn ($builder) => $builder->whereHas('pendaftaran', fn ($pendaftaran) => $pendaftaran->where('tahun_ajaran', $request->string('tahun_ajaran'))))
            ->when($status !== '', fn ($builder) => $builder->where('status_verifikasi', $status))
            ->when($request->filled('cari'), function ($builder) use ($request): void {
                $cari = $request->string('cari');
                $builder->whereHas('pendaftaran', function ($pendaftaran) use ($cari): void {
                    $pendaftaran->where('nomor_pendaftaran', 'like', "%{$cari}%")
                        ->orWhereHas('calonSiswa', fn ($calonSiswa) => $calonSiswa->where('nama_lengkap', 'like', "%{$cari}%"));
                });
            });

        $pembayaran = $query->paginate(10)->withQueryString();
        $dipilih = $request->filled('pembayaran')
            ? Pembayaran::query()->with(['pendaftaran.calonSiswa'])->find($request->integer('pembayaran'))
            : $pembayaran->first();

        return view('admin.verifikasi-pembayaran.index', [
            'pembayaran' => $pembayaran,
            'dipilih' => $dipilih,
            'tahunAjaran' => \App\Models\Pendaftaran::query()->select('tahun_ajaran')->distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran'),
        ]);
    }

    public function update(Request $request, Pembayaran $pembayaran): RedirectResponse
    {
        $data = $request->validate([
            'status_verifikasi' => ['required', 'in:valid,tidak_valid'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $pembayaran, $data): void {
            $pembayaran->update([
                'status_verifikasi' => $data['status_verifikasi'],
                'catatan_verifikasi' => $data['catatan'] ?? null,
                'diverifikasi_oleh' => $request->user()->admin?->id,
                'diverifikasi_pada' => now(),
            ]);

            $status = $data['status_verifikasi'] === 'valid' ? 'siap_keputusan' : 'perlu_perbaikan_pembayaran';
            $pembayaran->pendaftaran()->update(['status' => $status]);
            StatusPendaftaran::create([
                'pendaftaran_id' => $pembayaran->pendaftaran_id,
                'status' => $status,
                'catatan' => $status === 'siap_keputusan'
                    ? 'Pembayaran telah dinyatakan valid dan pendaftaran siap untuk diputuskan.'
                    : 'Bukti pembayaran perlu diperbaiki atau diunggah ulang.',
                'admin_id' => $request->user()->admin?->id,
            ]);
        });

        return to_route('admin.verifikasi-pembayaran.index', ['pembayaran' => $pembayaran->id])
            ->with('success', 'Verifikasi pembayaran berhasil disimpan.');
    }
}
