<?php

use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DokumenPendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\StatusPendaftaranController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPendaftaranController;
use App\Http\Controllers\AdminPendaftaranDetailController;
use App\Http\Controllers\AdminVerifikasiPembayaranController;
use App\Http\Controllers\AdminVerifikasiDokumenController;
use App\Http\Controllers\AdminDaftarDiterimaController;
use App\Http\Controllers\AdminLaporanController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])->middleware('guest')->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->middleware('guest')->name('login.simpan');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->middleware('auth')->name('logout');
    Route::get('/', [AdminDashboardController::class, 'index'])->middleware(['auth', 'admin'])->name('home');
    Route::get('/pendaftaran', [AdminPendaftaranController::class, 'index'])->middleware(['auth', 'admin'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminPendaftaranDetailController::class, 'show'])->middleware(['auth', 'admin'])->name('pendaftaran.detail');
    Route::patch('/pendaftaran/{pendaftaran}/dokumen/{dokumen}', [AdminPendaftaranDetailController::class, 'verifikasiDokumen'])->middleware(['auth', 'admin'])->name('pendaftaran.dokumen.verifikasi');
    Route::patch('/pendaftaran/{pendaftaran}/pembayaran/{pembayaran}', [AdminPendaftaranDetailController::class, 'verifikasiPembayaran'])->middleware(['auth', 'admin'])->name('pendaftaran.pembayaran.verifikasi');
    Route::patch('/pendaftaran/{pendaftaran}/keputusan', [AdminPendaftaranDetailController::class, 'keputusan'])->middleware(['auth', 'admin'])->name('pendaftaran.keputusan');
    Route::get('/pendaftaran/{pendaftaran}/dokumen/{dokumen}/file', [AdminPendaftaranDetailController::class, 'fileDokumen'])->middleware(['auth', 'admin'])->name('pendaftaran.dokumen.file');
    Route::get('/pendaftaran/{pendaftaran}/pembayaran/{pembayaran}/file', [AdminPendaftaranDetailController::class, 'filePembayaran'])->middleware(['auth', 'admin'])->name('pendaftaran.pembayaran.file');
    Route::get('/verifikasi-pembayaran', [AdminVerifikasiPembayaranController::class, 'index'])->middleware(['auth', 'admin'])->name('verifikasi-pembayaran.index');
    Route::patch('/verifikasi-pembayaran/{pembayaran}', [AdminVerifikasiPembayaranController::class, 'update'])->middleware(['auth', 'admin'])->name('verifikasi-pembayaran.update');
    Route::get('/verifikasi-dokumen', [AdminVerifikasiDokumenController::class, 'index'])->middleware(['auth', 'admin'])->name('verifikasi-dokumen.index');
    Route::patch('/verifikasi-dokumen/{pendaftaran}/dokumen/{dokumen}', [AdminVerifikasiDokumenController::class, 'update'])->middleware(['auth', 'admin'])->name('verifikasi-dokumen.update');
    Route::get('/daftar-diterima', [AdminDaftarDiterimaController::class, 'index'])->middleware(['auth', 'admin'])->name('daftar-diterima.index');
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->middleware(['auth', 'admin'])->name('laporan.index');
    Route::get('/laporan/export', [AdminLaporanController::class, 'export'])->middleware(['auth', 'admin'])->name('laporan.export');
});

Route::prefix('status-pendaftaran')->name('status.')->group(function () {
    Route::get('/', [StatusPendaftaranController::class, 'index'])->name('index');
    Route::post('/kirim-otp', [StatusPendaftaranController::class, 'kirimOtp'])->middleware('throttle:3,1')->name('otp.kirim');
    Route::post('/verifikasi-otp', [StatusPendaftaranController::class, 'verifikasiOtp'])->middleware('throttle:5,15')->name('otp.verifikasi');
    Route::get('/{pendaftaran:nomor_pendaftaran}', [StatusPendaftaranController::class, 'show'])->middleware('pendaftaran.access')->name('detail');
});

Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    Route::get('/', [PendaftaranController::class, 'informasi'])->name('informasi');
    Route::get('/formulir', [PendaftaranController::class, 'create'])->name('formulir');
    Route::post('/', [PendaftaranController::class, 'store'])->name('simpan');
    Route::get('/{pendaftaran:nomor_pendaftaran}/dokumen', [DokumenPendaftaranController::class, 'index'])
        ->middleware('pendaftaran.access')->name('dokumen');
    Route::post('/{pendaftaran:nomor_pendaftaran}/dokumen', [DokumenPendaftaranController::class, 'store'])
        ->middleware('pendaftaran.access')->name('dokumen.simpan');
    Route::get('/{pendaftaran:nomor_pendaftaran}/pembayaran', [PembayaranController::class, 'create'])
        ->middleware('pendaftaran.access')->name('pembayaran');
    Route::post('/{pendaftaran:nomor_pendaftaran}/pembayaran', [PembayaranController::class, 'store'])
        ->middleware('pendaftaran.access')->name('pembayaran.simpan');
});
