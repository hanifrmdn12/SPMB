<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('nomor_whatsapp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('nomor_whatsapp', 20);
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        Schema::create('calon_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik', 16)->nullable()->unique();
            $table->string('nisn', 10)->nullable()->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama');
            $table->text('alamat');
            $table->timestamps();
        });

        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->foreignId('calon_siswa_id')->constrained('calon_siswa')->cascadeOnDelete();
            $table->foreignId('orang_tua_id')->constrained('orang_tua')->restrictOnDelete();
            $table->string('tahun_ajaran', 9);
            $table->date('tanggal_pendaftaran');
            $table->enum('status', [
                'draft',
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'perlu_perbaikan',
                'diterima',
                'ditolak',
            ])->default('draft');
            $table->timestamps();
        });

        Schema::create('jenis_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->boolean('wajib')->default(true);
            $table->unsignedInteger('ukuran_maksimal_kb')->default(2048);
            $table->string('format_diizinkan')->default('pdf,jpg,jpeg,png');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('dokumen_persyaratan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->foreignId('jenis_dokumen_id')->constrained('jenis_dokumen')->restrictOnDelete();
            $table->string('path_file');
            $table->string('nama_file');
            $table->enum('status_verifikasi', ['menunggu', 'valid', 'tidak_valid'])->default('menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
            $table->unique(['pendaftaran_id', 'jenis_dokumen_id']);
        });

        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->date('tanggal_pembayaran');
            $table->string('bukti_pembayaran');
            $table->enum('status_verifikasi', ['menunggu', 'valid', 'tidak_valid'])->default('menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
        });

        Schema::create('status_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->enum('status', [
                'draft',
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'perlu_perbaikan',
                'diterima',
                'ditolak',
            ]);
            $table->text('catatan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pendaftaran');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('dokumen_persyaratan');
        Schema::dropIfExists('jenis_dokumen');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('calon_siswa');
        Schema::dropIfExists('orang_tua');
        Schema::dropIfExists('admins');
    }
};
