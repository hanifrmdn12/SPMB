<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $allStatuses = [
            'draft',
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'perlu_perbaikan',
            'diterima',
            'ditolak',
            'menunggu_dokumen',
            'perlu_perbaikan_dokumen',
            'menunggu_verifikasi_pembayaran',
            'perlu_perbaikan_pembayaran',
            'siap_keputusan',
        ];

        Schema::table('pendaftaran', function (Blueprint $table) use ($allStatuses): void {
            $table->enum('status', $allStatuses)->default('draft')->change();
        });
        Schema::table('status_pendaftaran', function (Blueprint $table) use ($allStatuses): void {
            $table->enum('status', $allStatuses)->change();
        });

        DB::table('pendaftaran')->where('status', 'draft')->update(['status' => 'menunggu_dokumen']);
        DB::table('pendaftaran')->where('status', 'menunggu_verifikasi')->update(['status' => 'menunggu_verifikasi_pembayaran']);
        DB::table('pendaftaran')->where('status', 'perlu_perbaikan')->update(['status' => 'perlu_perbaikan_dokumen']);
        DB::table('status_pendaftaran')->where('status', 'draft')->update(['status' => 'menunggu_dokumen']);
        DB::table('status_pendaftaran')->where('status', 'menunggu_verifikasi')->update(['status' => 'menunggu_verifikasi_pembayaran']);
        DB::table('status_pendaftaran')->where('status', 'perlu_perbaikan')->update(['status' => 'perlu_perbaikan_dokumen']);

        $statuses = [
            'menunggu_dokumen',
            'perlu_perbaikan_dokumen',
            'menunggu_pembayaran',
            'menunggu_verifikasi_pembayaran',
            'perlu_perbaikan_pembayaran',
            'siap_keputusan',
            'diterima',
            'ditolak',
        ];

        Schema::table('pendaftaran', function (Blueprint $table) use ($statuses): void {
            $table->enum('status', $statuses)->default('menunggu_dokumen')->change();
        });
        Schema::table('status_pendaftaran', function (Blueprint $table) use ($statuses): void {
            $table->enum('status', $statuses)->change();
        });
    }

    public function down(): void
    {
        $statuses = ['draft', 'menunggu_pembayaran', 'menunggu_verifikasi', 'perlu_perbaikan', 'diterima', 'ditolak'];

        $legacyStatusMappings = [
            'menunggu_dokumen' => 'draft',
            'perlu_perbaikan_dokumen' => 'perlu_perbaikan',
            'menunggu_verifikasi_pembayaran' => 'menunggu_verifikasi',
            'perlu_perbaikan_pembayaran' => 'perlu_perbaikan',
            'siap_keputusan' => 'menunggu_verifikasi',
        ];

        foreach ($legacyStatusMappings as $currentStatus => $legacyStatus) {
            DB::table('pendaftaran')->where('status', $currentStatus)->update(['status' => $legacyStatus]);
            DB::table('status_pendaftaran')->where('status', $currentStatus)->update(['status' => $legacyStatus]);
        }

        Schema::table('pendaftaran', function (Blueprint $table) use ($statuses): void {
            $table->enum('status', $statuses)->default('draft')->change();
        });
        Schema::table('status_pendaftaran', function (Blueprint $table) use ($statuses): void {
            $table->enum('status', $statuses)->change();
        });
    }
};
