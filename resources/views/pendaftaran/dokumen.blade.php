@extends('layouts.applicant')
@section('title', 'Unggah Dokumen — SPMB Sekolah')
@section('portal-title', 'Unggah Dokumen Persyaratan')
@section('portal-styles')
    .document-list{display:grid;gap:9px}.document-item{display:grid;grid-template-columns:38px minmax(145px,1fr) minmax(210px,1.35fr) auto;gap:12px;align-items:center;padding:11px;border:1px solid #d9e5da;border-radius:9px}.document-number{display:grid;width:29px;height:29px;place-items:center;border-radius:6px;color:var(--brand-deep);background:#eaf7ea;font-size:12px;font-weight:850}.document-name{font-size:12px;font-weight:850}.document-name small{display:block;margin-top:2px;color:var(--muted);font-size:10px;font-weight:650}.document-upload input{width:100%;padding:7px;border:1px solid #ceddce;border-radius:6px;background:#fff;font-size:11px}.document-state{display:inline-flex;align-items:center;gap:6px;min-width:104px;color:#79857c;font-size:10px}.document-state i{color:#a2ada5}.document-state.valid{color:#087441}.document-state.valid i{color:#12a559}.document-state.rejected{color:#a45a00}.document-state.rejected i{color:#e78a23}.document-footer{display:flex;justify-content:space-between;gap:12px;margin-top:18px;padding-top:14px;border-top:1px solid var(--line)}.document-footer .action:last-child{min-width:185px}@media(max-width:680px){.document-item{grid-template-columns:34px 1fr}.document-upload{grid-column:2}.document-state{grid-column:2}.document-footer{flex-direction:column-reverse}.document-footer .action{width:100%}}
@endsection
@section('portal-content')
@if(session('success'))<div class="alert success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<h2 class="content-section-title"><i class="fa-solid fa-folder-open"></i> Daftar Dokumen yang Harus Diunggah</h2>
<p class="content-section-subtitle">Format file: PDF, JPG, JPEG, atau PNG. Ukuran maksimal mengikuti ketentuan setiap dokumen.</p>
@php($dapatMengunggah = in_array($pendaftaran->status, ['menunggu_dokumen', 'perlu_perbaikan_dokumen'], true))
@if($dapatMengunggah)
    <form method="POST" action="{{ route('pendaftaran.dokumen.simpan', $pendaftaran) }}" enctype="multipart/form-data">
        @csrf
@endif
<div class="document-list">
@foreach($jenisDokumen as $index => $jenis)
    @php($unggahan = $pendaftaran->dokumen->firstWhere('jenis_dokumen_id', $jenis->id))
    <article class="document-item">
        <span class="document-number">{{ $index + 1 }}</span>
        <div class="document-name">
            {{ $jenis->nama }}
            <small>{{ $jenis->wajib ? 'Wajib diunggah' : 'Opsional' }}</small>
            @if($unggahan && $unggahan->catatan_verifikasi)
                <small style="color:#a45a00">Catatan: {{ $unggahan->catatan_verifikasi }}</small>
            @endif
        </div>
        @if($dapatMengunggah && ! $semuaDokumenTerunggah)
            <div class="document-upload"><input type="file" name="dokumen[{{ $jenis->id }}]" accept=".pdf,.jpg,.jpeg,.png" @if($jenis->wajib && (! $unggahan || $unggahan->status_verifikasi === 'tidak_valid')) required @endif></div>
        @else
            <div class="document-upload">{{ $unggahan?->nama_file ?? 'Belum ada berkas' }}</div>
        @endif
        <div class="document-state {{ $unggahan?->status_verifikasi === 'valid' ? 'valid' : ($unggahan?->status_verifikasi === 'tidak_valid' ? 'rejected' : '') }}"><i class="fa-solid {{ $unggahan?->status_verifikasi === 'valid' ? 'fa-circle-check' : ($unggahan?->status_verifikasi === 'tidak_valid' ? 'fa-circle-xmark' : 'fa-circle-exclamation') }}"></i>{{ $unggahan ? ($unggahan->status_verifikasi === 'tidak_valid' ? 'Perlu perbaikan' : ucfirst($unggahan->status_verifikasi)) : 'Belum diunggah' }}</div>
    </article>
@endforeach</div>
@if($dapatMengunggah && ! $semuaDokumenTerunggah)
    <footer class="document-footer"><a class="action secondary" href="{{ route('status.detail', $pendaftaran) }}"><i class="fa-solid fa-arrow-left"></i> Kembali</a><button class="action" type="submit">Simpan dan Lanjut <i class="fa-solid fa-arrow-right"></i></button></footer>
@endif
@if($dapatMengunggah)
    </form>
@endif
@if($pendaftaran->status === 'menunggu_pembayaran')<div class="stage-note" style="margin-top:18px"><i class="fa-solid fa-circle-check"></i><div><b>Dokumen sudah dikirim.</b><br>Anda dapat melanjutkan ke tahap pembayaran.</div></div><a class="action" href="{{ route('pendaftaran.pembayaran', $pendaftaran) }}">Lanjut ke pembayaran <i class="fa-solid fa-arrow-right"></i></a>@endif
@endsection
