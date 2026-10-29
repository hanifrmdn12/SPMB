<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $statusCounts = [
            'menunggu_verifikasi' => Pendaftaran::query()->whereIn('status', ['menunggu_dokumen', 'menunggu_verifikasi_pembayaran'])->count(),
            'dokumen_ok' => Pendaftaran::query()->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi_pembayaran', 'perlu_perbaikan_pembayaran', 'siap_keputusan', 'diterima'])->count(),
            'pembayaran_ok' => Pembayaran::query()->where('status_verifikasi', 'valid')->count(),
            'diterima' => Pendaftaran::query()->where('status', 'diterima')->count(),
        ];

        $bulan = collect(range(5, 0))->map(function (int $offset): array {
            $tanggal = now()->subMonths($offset);

            return [
                'label' => $tanggal->translatedFormat('M'),
                'total' => Pendaftaran::query()
                    ->whereBetween('tanggal_pendaftaran', [$tanggal->copy()->startOfMonth(), $tanggal->copy()->endOfMonth()])
                    ->count(),
            ];
        });

        return view('admin.home', [
            'ringkasan' => [
                'total_pendaftar' => Pendaftaran::count(),
                'menunggu_verifikasi' => $statusCounts['menunggu_verifikasi'],
                'pembayaran_masuk' => Pembayaran::count(),
                'diterima' => $statusCounts['diterima'],
            ],
            'pendaftaranTerbaru' => Pendaftaran::query()
                ->with('calonSiswa')
                ->latest('tanggal_pendaftaran')
                ->latest('id')
                ->take(5)
                ->get(),
            'bulan' => $bulan,
            'maksimumBulanan' => max(1, $bulan->max('total')),
            'statusCounts' => $statusCounts,
        ]);
    }
}
