<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminLaporanController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request);
        $laporan = $this->dataLaporan($query);

        return view('admin.laporan.index', [
            ...$laporan,
            'tahunAjaran' => Pendaftaran::query()->select('tahun_ajaran')->distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran'),
            'gelombang' => Pendaftaran::query()->whereNotNull('gelombang')->select('gelombang')->distinct()->orderBy('gelombang')->pluck('gelombang'),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $laporan = $this->dataLaporan($this->filteredQuery($request));

        return response()->streamDownload(function () use ($laporan): void {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Gelombang', 'Jumlah Pendaftar', 'Menunggu Verifikasi', 'Pembayaran OK', 'Dokumen OK', 'Diterima', 'Ditolak']);
            foreach ($laporan['perGelombang'] as $baris) {
                fputcsv($file, [$baris['gelombang'], $baris['total'], $baris['menunggu'], $baris['pembayaran_ok'], $baris['dokumen_ok'], $baris['diterima'], $baris['ditolak']]);
            }
            fclose($file);
        }, 'laporan-pendaftaran-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredQuery(Request $request): Builder
    {
        return Pendaftaran::query()
            ->when($request->filled('tahun_ajaran'), fn (Builder $query) => $query->where('tahun_ajaran', $request->string('tahun_ajaran')))
            ->when($request->filled('gelombang'), fn (Builder $query) => $query->where('gelombang', $request->string('gelombang')))
            ->when($request->filled('tanggal_awal'), fn (Builder $query) => $query->whereDate('tanggal_pendaftaran', '>=', $request->date('tanggal_awal')))
            ->when($request->filled('tanggal_akhir'), fn (Builder $query) => $query->whereDate('tanggal_pendaftaran', '<=', $request->date('tanggal_akhir')));
    }

    private function dataLaporan(Builder $query): array
    {
        $data = (clone $query)->get(['id', 'gelombang', 'status']);
        $dokumenOk = ['menunggu_pembayaran', 'menunggu_verifikasi_pembayaran', 'perlu_perbaikan_pembayaran', 'siap_keputusan', 'diterima', 'ditolak'];
        $pembayaranOk = ['siap_keputusan', 'diterima', 'ditolak'];
        $menunggu = ['menunggu_dokumen', 'menunggu_verifikasi_pembayaran'];

        $buatBaris = function (Collection $items) use ($dokumenOk, $pembayaranOk, $menunggu): array {
            return [
                'total' => $items->count(),
                'menunggu' => $items->whereIn('status', $menunggu)->count(),
                'pembayaran_ok' => $items->whereIn('status', $pembayaranOk)->count(),
                'dokumen_ok' => $items->whereIn('status', $dokumenOk)->count(),
                'diterima' => $items->where('status', 'diterima')->count(),
                'ditolak' => $items->where('status', 'ditolak')->count(),
            ];
        };

        $ringkasan = $buatBaris($data);
        $perGelombang = $data->groupBy(fn ($item) => $item->gelombang ?: 'Belum ditentukan')
            ->map(fn (Collection $items, string $gelombang) => ['gelombang' => $gelombang, ...$buatBaris($items)])
            ->sortBy('gelombang')
            ->values();

        return compact('ringkasan', 'perGelombang');
    }
}
