@extends('layouts.public')

@section('title', 'Login Admin — SPMB Sekolah')

@section('styles')
    .login-page { min-height: 100vh; display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(420px, .95fr); background: var(--canvas); }
    .login-intro { position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; padding: clamp(32px, 6vw, 86px); color: #fff; background: linear-gradient(145deg, var(--brand-deep), var(--brand-dark) 62%, var(--brand)); }
    .login-intro::before, .login-intro::after { position: absolute; border-radius: 999px; content: ''; pointer-events: none; }
    .login-intro::before { width: 430px; height: 430px; top: -185px; right: -145px; background: rgba(245, 255, 22, .16); }
    .login-intro::after { width: 310px; height: 310px; bottom: -145px; left: -100px; border: 44px solid rgba(255,255,255,.1); }
    .brand, .intro-copy, .intro-footer { position: relative; z-index: 1; }
    .brand { display: inline-flex; align-items: center; gap: 12px; width: fit-content; color: #fff; font-weight: 850; text-decoration: none; letter-spacing: -.02em; }
    .brand-mark { display: grid; width: 44px; height: 44px; place-items: center; border-radius: 14px; color: var(--brand-deep); background: var(--accent); box-shadow: 0 8px 20px rgba(0,0,0,.16); }
    .brand-mark i { font-size: 20px; }
    .brand small { display: block; margin-top: 2px; color: rgba(255,255,255,.72); font-size: 11px; font-weight: 650; letter-spacing: .04em; text-transform: uppercase; }
    .intro-copy { max-width: 570px; margin: 44px 0; }
    .eyebrow { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; padding: 7px 11px; border: 1px solid rgba(255,255,255,.24); border-radius: 999px; background: rgba(255,255,255,.1); font-size: 12px; font-weight: 750; }
    .intro-copy h1 { max-width: 620px; margin: 0; font-size: clamp(35px, 4.1vw, 58px); line-height: 1.07; letter-spacing: -.055em; }
    .intro-copy p { max-width: 470px; margin: 20px 0 0; color: rgba(255,255,255,.82); font-size: 16px; line-height: 1.7; }
    .intro-points { display: grid; gap: 12px; margin: 30px 0 0; padding: 0; list-style: none; }
    .intro-points li { display: flex; align-items: center; gap: 11px; font-size: 14px; font-weight: 650; }
    .intro-points i { display: grid; width: 26px; height: 26px; place-items: center; border-radius: 50%; color: var(--brand-deep); background: var(--accent); font-size: 12px; }
    .intro-footer { color: rgba(255,255,255,.64); font-size: 13px; }
    .login-panel { display: grid; min-height: 100%; place-items: center; padding: 40px 28px; }
    .login-card { width: min(100%, 430px); }
    .panel-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 42px; }
    .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--muted); font-size: 13px; font-weight: 700; text-decoration: none; transition: color .2s; }
    .back-link:hover { color: var(--brand-dark); }
    .panel-badge { display: grid; width: 42px; height: 42px; place-items: center; border-radius: 13px; color: var(--brand-dark); background: #e6fbe4; font-size: 18px; }
    .login-card h2 { margin: 0; color: var(--ink); font-size: 32px; letter-spacing: -.045em; }
    .login-card > p { margin: 10px 0 28px; color: var(--muted); font-size: 14px; line-height: 1.65; }
    .alert { display: flex; gap: 11px; margin: 0 0 20px; padding: 13px 14px; border-radius: 12px; font-size: 13px; line-height: 1.5; }
    .alert-success { color: #0d6925; background: #e7f9e8; border: 1px solid #c3eec7; }
    .alert-error { color: #a12323; background: #fff0f0; border: 1px solid #ffd1d1; }
    .alert ul { margin: 0; padding-left: 18px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; margin-bottom: 8px; color: #324438; font-size: 13px; font-weight: 780; }
    .input-wrap { position: relative; }
    .input-wrap > i { position: absolute; top: 50%; left: 15px; color: #7b8c80; transform: translateY(-50%); }
    .input-wrap input { width: 100%; padding: 14px 15px 14px 43px; border: 1px solid var(--line); border-radius: 13px; outline: none; color: var(--ink); background: #fff; font: inherit; transition: border .2s, box-shadow .2s; }
    .input-wrap input:focus { border-color: var(--brand); box-shadow: 0 0 0 4px rgba(52,205,52,.13); }
    .form-options { display: flex; align-items: center; gap: 9px; margin: 4px 0 24px; color: var(--muted); font-size: 13px; }
    .form-options input { width: 16px; height: 16px; accent-color: var(--brand-dark); }
    .login-button { display: inline-flex; width: 100%; align-items: center; justify-content: center; gap: 9px; padding: 14px 20px; border: 0; border-radius: 13px; cursor: pointer; color: #133612; background: var(--accent); box-shadow: 0 12px 24px rgba(216,224,0,.25); font: inherit; font-weight: 850; transition: transform .2s, box-shadow .2s; }
    .login-button:hover { transform: translateY(-2px); box-shadow: 0 16px 28px rgba(216,224,0,.32); }
    .login-note { display: flex; justify-content: center; gap: 8px; margin: 24px 0 0; color: #819086; font-size: 12px; }
    .login-note i { color: var(--brand); }
    @media (max-width: 800px) { .login-page { display: block; } .login-intro { min-height: auto; padding: 28px 26px 34px; } .intro-copy { margin: 48px 0 10px; } .intro-copy h1 { font-size: 36px; } .intro-points, .intro-footer { display: none; } .login-panel { padding: 38px 24px 48px; } .panel-top { margin-bottom: 30px; } }
@endsection

@section('content')
    <main class="login-page">
        <section class="login-intro">
            <a class="brand" href="{{ route('landing') }}">
                <span class="brand-mark"><i class="fa-solid fa-graduation-cap"></i></span>
                <span>SPMB Sekolah<small>Sistem Penerimaan Murid Baru</small></span>
            </a>
            <div class="intro-copy">
                <span class="eyebrow"><i class="fa-solid fa-shield-halved"></i> Area terbatas panitia</span>
                <h1>Kelola penerimaan siswa dengan lebih terarah.</h1>
                <p>Masuk ke panel administrasi untuk memantau pendaftaran, memeriksa dokumen, dan memverifikasi pembayaran.</p>
                <ul class="intro-points">
                    <li><i class="fa-solid fa-check"></i> Data pendaftaran terpusat</li>
                    <li><i class="fa-solid fa-check"></i> Verifikasi lebih cepat dan tertelusur</li>
                </ul>
            </div>
            <div class="intro-footer">© {{ date('Y') }} SPMB Sekolah</div>
        </section>

        <section class="login-panel">
            <div class="login-card">
                <div class="panel-top">
                    <a class="back-link" href="{{ route('landing') }}"><i class="fa-solid fa-arrow-left"></i> Kembali ke beranda</a>
                    <span class="panel-badge"><i class="fa-solid fa-user-shield"></i></span>
                </div>
                <h2>Masuk sebagai admin</h2>
                <p>Gunakan akun admin yang telah terdaftar untuk melanjutkan.</p>
                @if (session('success'))
                    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span></div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('admin.login.simpan') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Alamat email</label>
                        <div class="input-wrap"><i class="fa-solid fa-envelope"></i><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@sekolah.sch.id"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Kata sandi</label>
                        <div class="input-wrap"><i class="fa-solid fa-lock"></i><input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi"></div>
                    </div>
                    <label class="form-options"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
                    <button class="login-button" type="submit">Masuk ke dashboard <i class="fa-solid fa-arrow-right"></i></button>
                </form>
                <p class="login-note"><i class="fa-solid fa-lock"></i> Akses ini hanya untuk panitia dan administrator sekolah.</p>
            </div>
        </section>
    </main>
@endsection
