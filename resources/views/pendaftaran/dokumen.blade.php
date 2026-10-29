@extends('layouts.applicant')
@section('title','Unggah Dokumen — SPMB Sekolah')
@section('portal-title','Unggah dokumen persyaratan')
@section('portal-styles')
.doc-list{display:grid;gap:12px}.doc{display:grid;grid-template-columns:1fr auto;gap:15px;align-items:center;padding:16px;border:1px solid var(--line);border-radius:14px}.doc h2{margin:0 0 4px;font-size:14px}.doc p{margin:0;color:var(--muted);font-size:12px}.doc-status{display:inline-flex;margin-top:8px;padding:4px 8px;border-radius:99px;background:#f0f3f0;color:#667269;font-size:10px;font-weight:800}.doc-status.valid{color:#087441;background:#ddf7e7}.doc-status.tidak_valid{color:#b45a00;background:#fff0df}.upload{min-width:260px}.upload input{max-width:100%;font-size:11px}.submit-all{display:flex;justify-content:flex-end;margin-top:18px}.submit-all button{border:0;border-radius:11px;padding:12px 16px;cursor:pointer;color:#153c19;background:var(--accent);font-weight:850}@media(max-width:650px){.doc{grid-template-columns:1fr}.upload{min-width:0}.submit-all button{width:100%}}
@endsection
@section('portal-content')
@if(session('success'))<div class="alert success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>@endif
@if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<div class="stage-note"><i class="fa-solid fa-circle-info"></i><div><b>Unggah semua dokumen wajib.</b><br>Dokumen akan diperiksa oleh panitia sebelum tahap pembayaran dibuka.</div></div>
@if($semuaDokumenTerunggah && $pendaftaran->status === 'menunggu_pembayaran')
    <div class="stage-note"><i class="fa-solid fa-circle-check"></i><div><b>Dokumen sudah dikirim.</b><br>Anda dapat melanjutkan pembayaran. Panitia akan memeriksa dokumen Anda dalam proses.</div></div>
@endif
@php($dapatMengunggah = in_array($pendaftaran->status, ['menunggu_dokumen', 'perlu_perbaikan_dokumen'], true))
@if($dapatMengunggah)
    <form method="POST" action="{{ route('pendaftaran.dokumen.simpan', $pendaftaran) }}" enctype="multipart/form-data">
        @csrf
@endif
        <div class="doc-list">
            @foreach($jenisDokumen as $jenis)
                @php($unggahan = $pendaftaran->dokumen->firstWhere('jenis_dokumen_id', $jenis->id))
                <article class="doc">
                    <div>
                        <h2>{{ $jenis->nama }} @if($jenis->wajib)<span style="color:#b22">*</span>@endif</h2>
                        @if($unggahan)
                            <p>{{ $unggahan->nama_file }}</p>
                            <span class="doc-status {{ $unggahan->status_verifikasi }}">{{ $unggahan->status_verifikasi === 'tidak_valid' ? 'Perlu perbaikan' : ucwords($unggahan->status_verifikasi) }}</span>
                            @if($unggahan->catatan_verifikasi)<p style="margin-top:7px;color:#a45a00"><b>Catatan panitia:</b> {{ $unggahan->catatan_verifikasi }}</p>@endif
                        @else
                            <p>PDF, JPG, JPEG, atau PNG · maksimum {{ $jenis->ukuran_maksimal_kb / 1024 }} MB</p>
                        @endif
                    </div>
                    @if($dapatMengunggah && ! $semuaDokumenTerunggah)
                        <div class="upload"><input type="file" name="dokumen[{{ $jenis->id }}]" accept=".pdf,.jpg,.jpeg,.png" @if($jenis->wajib && (! $unggahan || $unggahan->status_verifikasi === 'tidak_valid')) required @endif></div>
                    @endif
                </article>
            @endforeach
        </div>
@if($dapatMengunggah && ! $semuaDokumenTerunggah)
        <div class="submit-all"><button type="submit"><i class="fa-solid fa-cloud-arrow-up"></i> Unggah semua dokumen</button></div>
@endif
@if($dapatMengunggah)
    </form>
@endif
@if($pendaftaran->status==='menunggu_pembayaran')<div class="stage-note" style="margin-top:20px"><i class="fa-solid fa-circle-check"></i><div><b>Dokumen sudah dikirim.</b><br>Anda dapat melanjutkan ke tahap pembayaran.</div></div><a class="action" href="{{ route('pendaftaran.pembayaran',$pendaftaran) }}">Lanjut ke pembayaran <i class="fa-solid fa-arrow-right"></i></a>@endif
@endsection
