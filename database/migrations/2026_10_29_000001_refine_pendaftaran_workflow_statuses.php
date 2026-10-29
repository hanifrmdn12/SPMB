<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $allStatuses = "'draft','menunggu_pembayaran','menunggu_verifikasi','perlu_perbaikan','diterima','ditolak','menunggu_dokumen','perlu_perbaikan_dokumen','menunggu_verifikasi_pembayaran','perlu_perbaikan_pembayaran','siap_keputusan'";
        DB::statement("ALTER TABLE pendaftaran MODIFY status ENUM($allStatuses) NOT NULL DEFAULT 'draft'");
        DB::statement("ALTER TABLE status_pendaftaran MODIFY status ENUM($allStatuses) NOT NULL");

        DB::table('pendaftaran')->where('status', 'draft')->update(['status' => 'menunggu_dokumen']);
        DB::table('pendaftaran')->where('status', 'menunggu_verifikasi')->update(['status' => 'menunggu_verifikasi_pembayaran']);
        DB::table('pendaftaran')->where('status', 'perlu_perbaikan')->update(['status' => 'perlu_perbaikan_dokumen']);
        DB::table('status_pendaftaran')->where('status', 'draft')->update(['status' => 'menunggu_dokumen']);
        DB::table('status_pendaftaran')->where('status', 'menunggu_verifikasi')->update(['status' => 'menunggu_verifikasi_pembayaran']);
        DB::table('status_pendaftaran')->where('status', 'perlu_perbaikan')->update(['status' => 'perlu_perbaikan_dokumen']);

        $statuses = "'menunggu_dokumen','perlu_perbaikan_dokumen','menunggu_pembayaran','menunggu_verifikasi_pembayaran','perlu_perbaikan_pembayaran','siap_keputusan','diterima','ditolak'";
        DB::statement("ALTER TABLE pendaftaran MODIFY status ENUM($statuses) NOT NULL DEFAULT 'menunggu_dokumen'");
        DB::statement("ALTER TABLE status_pendaftaran MODIFY status ENUM($statuses) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pendaftaran MODIFY status ENUM('draft','menunggu_pembayaran','menunggu_verifikasi','perlu_perbaikan','diterima','ditolak') NOT NULL DEFAULT 'draft'");
        DB::statement("ALTER TABLE status_pendaftaran MODIFY status ENUM('draft','menunggu_pembayaran','menunggu_verifikasi','perlu_perbaikan','diterima','ditolak') NOT NULL");
    }
};
