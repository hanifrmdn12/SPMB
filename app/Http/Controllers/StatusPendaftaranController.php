<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Services\WhatsAppOtpSender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Throwable;

class StatusPendaftaranController extends Controller
{
    public function index(): View
    {
        return view('status.index');
    }

    public function kirimOtp(Request $request, WhatsAppOtpSender $pengirim): RedirectResponse
    {
        $data = $request->validate(['nomor_pendaftaran' => ['required', 'string', 'max:50']]);
        $nomor = strtoupper(trim($data['nomor_pendaftaran']));
        $pendaftaran = Pendaftaran::query()->with('orangTua')->where('nomor_pendaftaran', $nomor)->first();

        if ($pendaftaran === null || empty($pendaftaran->orangTua?->nomor_whatsapp)) {
            return back()->withInput()->withErrors(['nomor_pendaftaran' => 'Nomor pendaftaran tidak ditemukan.']);
        }

        $kode = (string) random_int(100000, 999999);
        $pendaftaran->update([
            'otp_kode' => Hash::make($kode),
            'otp_kadaluarsa_pada' => now()->addMinutes(10),
            'otp_percobaan' => 0,
            'otp_dikirim_pada' => now(),
        ]);

        try {
            $pengirim->send($pendaftaran->orangTua->nomor_whatsapp, $kode);
        } catch (Throwable $exception) {
            report($exception);
            return back()->withInput()->withErrors(['nomor_pendaftaran' => $exception->getMessage()]);
        }

        return to_route('status.index')
            ->with('otp_nomor', $nomor)
            ->with('success', 'Kode OTP telah dikirim ke nomor WhatsApp pendaftar.')
            ->when(app()->environment('local'), fn ($redirect) => $redirect->with('development_otp', $kode));
    }

    public function verifikasiOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nomor_pendaftaran' => ['required', 'string', 'max:50'],
            'kode_otp' => ['required', 'digits:6'],
        ]);
        $nomor = strtoupper(trim($data['nomor_pendaftaran']));
        $pendaftaran = Pendaftaran::query()->where('nomor_pendaftaran', $nomor)->first();

        if ($pendaftaran === null || $pendaftaran->otp_kode === null || $pendaftaran->otp_kadaluarsa_pada?->isPast() || $pendaftaran->otp_percobaan >= 5) {
            return back()->withErrors(['kode_otp' => 'Kode OTP tidak berlaku. Silakan kirim kode baru.']);
        }

        if (! Hash::check($data['kode_otp'], $pendaftaran->otp_kode)) {
            $pendaftaran->increment('otp_percobaan');
            return back()->withErrors(['kode_otp' => 'Kode OTP tidak sesuai.']);
        }

        $pendaftaran->update(['otp_kode' => null, 'otp_kadaluarsa_pada' => null, 'otp_percobaan' => 0]);
        $request->session()->regenerate();
        $request->session()->put("pendaftaran_access.{$pendaftaran->id}", true);

        return $this->lanjutkanKeTahapAktif($pendaftaran);
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        $pendaftaran->load([
            'calonSiswa', 'dokumen.jenisDokumen', 'pembayaran',
            'riwayatStatus' => fn ($query) => $query->orderBy('dibuat_pada'),
        ]);

        return view('status.detail', compact('pendaftaran'));
    }

    private function lanjutkanKeTahapAktif(Pendaftaran $pendaftaran): RedirectResponse
    {
        if (in_array($pendaftaran->status, ['menunggu_dokumen', 'perlu_perbaikan_dokumen'], true)) {
            return to_route('pendaftaran.dokumen', $pendaftaran);
        }

        if (in_array($pendaftaran->status, ['menunggu_pembayaran', 'perlu_perbaikan_pembayaran'], true)) {
            return to_route('pendaftaran.pembayaran', $pendaftaran);
        }

        return to_route('status.detail', $pendaftaran);
    }
}
