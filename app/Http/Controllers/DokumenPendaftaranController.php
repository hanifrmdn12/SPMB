<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadDokumenRequest;
use App\Models\JenisDokumen;
use App\Models\Pendaftaran;
use App\Models\StatusPendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DokumenPendaftaranController extends Controller
{
    public function index(Pendaftaran $pendaftaran): View
    {
        $pendaftaran->load('dokumen.jenisDokumen');

        return view('pendaftaran.dokumen', [
            'pendaftaran' => $pendaftaran,
            'jenisDokumen' => JenisDokumen::query()->where('aktif', true)->orderBy('nama')->get(),
            'semuaDokumenTerunggah' => $this->semuaDokumenWajibTerunggah($pendaftaran),
        ]);
    }

    public function store(UploadDokumenRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        if (! in_array($pendaftaran->status, ['menunggu_dokumen', 'perlu_perbaikan_dokumen'], true)) {
            return to_route('pendaftaran.dokumen', $pendaftaran)
                ->with('error', 'Dokumen tidak dapat diubah pada tahap proses saat ini.');
        }

        $berkas = collect($request->file('dokumen', []))->filter();
        $dokumenWajib = JenisDokumen::query()->where('aktif', true)->where('wajib', true)->pluck('id');
        $sudahAda = $pendaftaran->dokumen()->where('status_verifikasi', '!=', 'tidak_valid')->pluck('jenis_dokumen_id');
        $belumLengkap = $dokumenWajib->diff($sudahAda->merge($berkas->keys()));

        if ($belumLengkap->isNotEmpty()) {
            throw ValidationException::withMessages(['dokumen' => 'Pilih seluruh dokumen wajib sebelum mengunggah.']);
        }

        foreach ($berkas as $jenisDokumenId => $file) {
            $jenisDokumen = JenisDokumen::query()->where('aktif', true)->findOrFail($jenisDokumenId);
            $dokumenSebelumnya = $pendaftaran->dokumen()->where('jenis_dokumen_id', $jenisDokumen->id)->first();
            $path = $file->store("pendaftaran/{$pendaftaran->nomor_pendaftaran}/dokumen", 'local');

            if ($dokumenSebelumnya !== null && Storage::disk('local')->exists($dokumenSebelumnya->path_file)) {
                Storage::disk('local')->delete($dokumenSebelumnya->path_file);
            }

            $pendaftaran->dokumen()->updateOrCreate(
                ['jenis_dokumen_id' => $jenisDokumen->id],
                [
                    'path_file' => $path,
                    'nama_file' => $file->getClientOriginalName(),
                    'status_verifikasi' => 'menunggu',
                    'catatan_verifikasi' => null,
                    'diverifikasi_oleh' => null,
                    'diverifikasi_pada' => null,
                ],
            );
        }

        $pendaftaran->update(['status' => 'menunggu_pembayaran']);
        StatusPendaftaran::create([
            'pendaftaran_id' => $pendaftaran->id,
            'status' => 'menunggu_pembayaran',
            'catatan' => 'Dokumen persyaratan berhasil diunggah. Silakan lanjutkan pembayaran.',
        ]);

        return to_route('pendaftaran.pembayaran', $pendaftaran)
            ->with('success', 'Dokumen berhasil diunggah. Silakan lanjutkan ke pembayaran.');
    }

    private function semuaDokumenWajibTerunggah(Pendaftaran $pendaftaran): bool
    {
        $dokumenWajib = JenisDokumen::query()->where('aktif', true)->where('wajib', true)->pluck('id');

        return $dokumenWajib->isNotEmpty()
            && $pendaftaran->dokumen()->whereIn('jenis_dokumen_id', $dokumenWajib)->count() === $dokumenWajib->count();
    }
}
