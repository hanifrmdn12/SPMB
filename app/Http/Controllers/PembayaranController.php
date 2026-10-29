<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranRequest;
use App\Models\JenisDokumen;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\StatusPendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function create(Pendaftaran $pendaftaran): View|RedirectResponse
    {
        if (! $this->dokumenLengkap($pendaftaran)) {
            return to_route('pendaftaran.dokumen', $pendaftaran)
                ->with('error', 'Unggah seluruh dokumen wajib sebelum melakukan pembayaran.');
        }

        return view('pendaftaran.pembayaran', [
            'pendaftaran' => $pendaftaran,
            'riwayatPembayaran' => $pendaftaran->pembayaran()->latest()->get(),
            'dapatMengunggah' => in_array($pendaftaran->status, ['menunggu_pembayaran', 'perlu_perbaikan_pembayaran'], true),
        ]);
    }

    public function store(StorePembayaranRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        if (! $this->dokumenLengkap($pendaftaran)) {
            return to_route('pendaftaran.dokumen', $pendaftaran)
                ->with('error', 'Unggah seluruh dokumen wajib sebelum melakukan pembayaran.');
        }

        if (! in_array($pendaftaran->status, ['menunggu_pembayaran', 'perlu_perbaikan_pembayaran'], true)) {
            return to_route('pendaftaran.pembayaran', $pendaftaran)
                ->with('error', 'Bukti pembayaran sedang diverifikasi dan belum dapat diunggah ulang.');
        }

        $buktiPembayaran = $request->file('bukti_pembayaran');
        $path = $buktiPembayaran->store("pendaftaran/{$pendaftaran->nomor_pendaftaran}/pembayaran", 'local');

        Pembayaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'nominal' => $request->validated('nominal'),
            'tanggal_pembayaran' => $request->validated('tanggal_pembayaran'),
            'bukti_pembayaran' => $path,
            'status_verifikasi' => 'menunggu',
        ]);

        $pendaftaran->update(['status' => 'menunggu_verifikasi_pembayaran']);

        StatusPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'status' => 'menunggu_verifikasi_pembayaran',
            'catatan' => 'Bukti pembayaran telah diunggah dan menunggu verifikasi admin.',
        ]);

        return to_route('pendaftaran.pembayaran', $pendaftaran)
            ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi.');
    }

    private function dokumenLengkap(Pendaftaran $pendaftaran): bool
    {
        $dokumenWajib = JenisDokumen::query()->where('aktif', true)->where('wajib', true)->pluck('id');

        return $dokumenWajib->isNotEmpty()
            && $pendaftaran->dokumen()->whereIn('jenis_dokumen_id', $dokumenWajib)->count() === $dokumenWajib->count();
    }
}
