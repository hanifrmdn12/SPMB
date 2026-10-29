@extends('layouts.public')

@section('title', 'SPMB Sekolah - Pendaftaran Siswa Baru')

@section('styles')
    .site-header { position: absolute; inset: 0 0 auto; z-index: 4; padding: 22px 0; color: var(--white); }
    .nav { display: flex; align-items: center; justify-content: space-between; gap: 22px; }
    .brand { display: flex; align-items: center; gap: 11px; text-decoration: none; }
    .brand-mark { width: 42px; height: 42px; display: grid; place-items: center; border: 2px solid var(--accent); border-radius: 13px; color: var(--brand-deep); background: var(--accent); font-weight: 900; letter-spacing: -.08em; }
    .brand-text strong { display: block; font-size: 15px; letter-spacing: .02em; }
    .brand-text span { display: block; opacity: .75; font-size: 11px; }
    .nav-links { display: flex; align-items: center; gap: 27px; }
    .nav-links > a { color: var(--white); font-size: 13px; font-weight: 600; text-decoration: none; opacity: .9; }
    .nav-links > a:hover { color: var(--accent); }
    .nav-admin { padding: 9px 14px; border: 1px solid rgba(255,255,255,.5); border-radius: 999px; }
    .hero { position: relative; overflow: hidden; min-height: 715px; padding: 150px 0 88px; color: var(--white); background: radial-gradient(circle at 81% 24%, rgba(245,255,22,.28), transparent 20%), radial-gradient(circle at 35% 110%, rgba(88,248,100,.28), transparent 31%), linear-gradient(128deg, #06451e 0%, #117c2d 50%, #32c936 116%); }
    .hero::after { position: absolute; width: 390px; height: 390px; right: -120px; bottom: -200px; border: 44px solid rgba(245,255,22,.16); border-radius: 50%; content: ""; }
    .hero-grid { position: relative; z-index: 1; display: grid; grid-template-columns: 1.04fr .96fr; align-items: center; gap: 46px; }
    .eyebrow { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 19px; padding: 8px 12px; border: 1px solid rgba(245,255,22,.42); border-radius: 999px; color: var(--accent); background: rgba(0, 44, 16, .26); font-size: 12px; font-weight: 700; }
    .eyebrow::before { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 0 5px rgba(245,255,22,.13); content: ""; }
    .hero h1 { max-width: 650px; margin: 0; font-size: clamp(43px, 5.1vw, 70px); line-height: 1.03; letter-spacing: -.058em; }
    .hero h1 em { color: var(--accent); font-style: normal; }
    .hero-copy { max-width: 535px; margin: 23px 0 31px; color: rgba(255,255,255,.82); font-size: 17px; line-height: 1.7; }
    .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .hero-note { display: flex; align-items: center; gap: 9px; margin-top: 25px; color: rgba(255,255,255,.72); font-size: 12px; }
    .hero-note b { color: var(--accent); }
    .portal-card { position: relative; max-width: 455px; margin-left: auto; padding: 23px; border: 1px solid rgba(255,255,255,.44); border-radius: 24px; background: rgba(255,255,255,.13); box-shadow: 0 26px 70px rgba(0, 45, 15, .32); backdrop-filter: blur(12px); }
    .portal-top { display: flex; align-items: center; justify-content: space-between; padding-bottom: 18px; border-bottom: 1px solid rgba(255,255,255,.22); }
    .portal-top small { display: block; margin-bottom: 4px; color: rgba(255,255,255,.62); }
    .portal-top strong { font-size: 17px; }
    .status-dot { display: inline-flex; align-items: center; gap: 6px; padding: 7px 9px; border-radius: 99px; color: #e9ffbd; background: rgba(24,92,29,.7); font-size: 11px; font-weight: 700; }
    .status-dot i { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); }
    .portal-steps { display: grid; gap: 11px; padding: 20px 0 17px; }
    .portal-step { display: flex; align-items: center; gap: 13px; padding: 12px; border-radius: 14px; background: rgba(255,255,255,.10); }
    .step-number { flex: none; width: 31px; height: 31px; display: grid; place-items: center; border-radius: 10px; color: var(--brand-deep); background: var(--accent); font-size: 12px; font-weight: 900; }
    .portal-step:nth-child(2) .step-number { color: var(--white); background: var(--brand); border: 1px solid rgba(255,255,255,.45); }
    .portal-step:nth-child(3) .step-number { color: var(--brand-deep); background: var(--white); }
    .portal-step strong { display: block; font-size: 13px; }.portal-step span { color: rgba(255,255,255,.66); font-size: 11px; }
    .portal-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px; border-radius: 15px; color: var(--brand-deep); background: var(--accent); }.portal-footer b { display: block; font-size: 12px; }.portal-footer span { font-size: 11px; }.portal-footer a { font-weight: 850; font-size: 12px; text-decoration: none; }
    .floating-chip { position: absolute; z-index: 2; left: -27px; bottom: 79px; padding: 12px 15px; border-radius: 13px; color: var(--ink); background: var(--white); box-shadow: var(--shadow); font-size: 12px; font-weight: 750; }.floating-chip span { color: var(--brand-dark); }
    .trust { position: relative; z-index: 2; margin-top: -39px; }.trust-card { display: grid; grid-template-columns: 1.45fr repeat(3, 1fr); align-items: center; overflow: hidden; border: 1px solid var(--line); border-radius: 18px; background: var(--white); box-shadow: var(--shadow); }.trust-intro { padding: 20px 25px; color: var(--muted); font-size: 13px; }.trust-intro b { display: block; margin-bottom: 3px; color: var(--ink); font-size: 15px; }.trust-stat { min-height: 78px; padding: 16px 20px; border-left: 1px solid var(--line); }.trust-stat b { display: block; color: var(--brand-dark); font-size: 23px; }.trust-stat span { color: var(--muted); font-size: 11px; }
    .why { padding: 104px 0 94px; }.section-kicker { color: var(--brand-dark); font-size: 12px; font-weight: 850; letter-spacing: .12em; text-transform: uppercase; }.section-heading { max-width: 575px; margin: 10px 0 36px; font-size: clamp(30px,4vw,47px); line-height: 1.08; letter-spacing: -.042em; }.benefits { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }.benefit { padding: 25px; border: 1px solid var(--line); border-radius: 16px; background: var(--white); transition: transform .2s, box-shadow .2s; }.benefit:hover { transform: translateY(-5px); box-shadow: var(--shadow); }.benefit-icon { width: 44px; height: 44px; display: grid; place-items: center; margin-bottom: 21px; border-radius: 13px; color: var(--brand-deep); background: linear-gradient(135deg, var(--accent), #faff9a); font-size: 20px; }.benefit h3 { margin: 0 0 8px; font-size: 16px; }.benefit p { margin: 0; color: var(--muted); font-size: 13px; line-height: 1.65; }
    .cta { padding: 0 0 74px; }.cta-box { position: relative; overflow: hidden; padding: 48px 55px; border-radius: 24px; color: var(--white); background: linear-gradient(115deg, var(--brand-deep), var(--brand-dark)); }.cta-box::after { position: absolute; width: 260px; height: 260px; top: -125px; right: 4%; border: 35px solid rgba(245,255,22,.22); border-radius: 50%; content: ""; }.cta-box h2 { position: relative; z-index: 1; max-width: 580px; margin: 0 0 11px; font-size: clamp(28px,3.4vw,42px); line-height: 1.1; letter-spacing: -.04em; }.cta-box p { position: relative; z-index: 1; max-width: 540px; margin: 0 0 25px; color: rgba(255,255,255,.76); }.footer { padding: 24px 0; color: var(--muted); border-top: 1px solid var(--line); font-size: 12px; }.footer-inner { display: flex; justify-content: space-between; gap: 20px; }
    @media (max-width: 850px) { .hero { min-height: auto; padding-top: 128px; }.hero-grid { grid-template-columns: 1fr; }.portal-card { width: min(100%, 500px); margin: 10px 0 0; }.trust-card { grid-template-columns: 1fr 1fr; }.trust-intro { grid-column: span 2; }.trust-stat:first-of-type { border-left: 0; }.benefits { grid-template-columns: 1fr; } }
    @media (max-width: 620px) { .container { width: min(100% - 32px, 1180px); }.nav-links > a:not(.nav-admin) { display: none; }.brand-text strong { font-size: 13px; }.hero { padding-bottom: 72px; }.hero-copy { font-size: 15px; }.hero-actions { display: grid; }.floating-chip { position: static; display: inline-block; margin: 15px 0 -4px; }.trust { margin-top: -20px; }.trust-card { grid-template-columns: 1fr; }.trust-intro { grid-column: auto; }.trust-stat, .trust-stat:first-of-type { border-top: 1px solid var(--line); border-left: 0; }.why { padding: 75px 0; }.cta-box { padding: 34px 27px; }.footer-inner { display: grid; } }
@endsection

@section('content')
    <header class="site-header">
        <div class="container nav">
            <a class="brand" href="{{ route('landing') }}">
                <span class="brand-mark"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
                <span class="brand-text"><strong>SPMB SEKOLAH</strong><span>Sistem Pendaftaran Siswa</span></span>
            </a>
            <nav class="nav-links">
                <a href="#tentang">Tentang</a><a href="#alur">Alur Pendaftaran</a><a href="{{ route('status.index') }}">Cek Status</a><a class="nav-admin" href="{{ route('admin.login') }}">Login Admin →</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-grid">
                <div>
                    <span class="eyebrow">PENDAFTARAN SISWA BARU 2026/2027</span>
                    <h1>Masa depan terbaik dimulai <em>dari sini.</em></h1>
                    <p class="hero-copy">Temukan pengalaman belajar yang bertumbuh bersama minat, karakter, dan prestasi. Mulai perjalanan pendidikan putra-putri Anda melalui pendaftaran yang mudah dan transparan.</p>
                    <div class="hero-actions"><a class="button button-primary" href="{{ route('pendaftaran.formulir') }}">Daftar Sekarang <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a><a class="button button-secondary" href="{{ route('pendaftaran.informasi') }}">Lihat Persyaratan <i class="fa-solid fa-list-check" aria-hidden="true"></i></a></div>
                    <p class="hero-note"><b><i class="fa-solid fa-circle-check" aria-hidden="true"></i></b> Pendaftaran online dibuka hingga 30 Juni 2027</p>
                </div>
                <div class="portal-card" id="alur">
                    <div class="portal-top"><div><small>Pendaftaran Online</small><strong>Mulai dalam 3 langkah</strong></div><span class="status-dot"><i></i> Dibuka</span></div>
                    <div class="portal-steps"><div class="portal-step"><span class="step-number"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></span><div><strong>Isi formulir</strong><span>Lengkapi data calon siswa</span></div></div><div class="portal-step"><span class="step-number"><i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i></span><div><strong>Unggah dokumen</strong><span>Kirim berkas persyaratan</span></div></div><div class="portal-step"><span class="step-number"><i class="fa-solid fa-chart-line" aria-hidden="true"></i></span><div><strong>Pantau status</strong><span>Ikuti proses pendaftaran</span></div></div></div>
                    <div class="portal-footer"><div><b>Sudah mendaftar?</b><span>Lihat perkembangan pendaftaran Anda.</span></div><a href="{{ route('status.index') }}">Cek status <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div>
                    <div class="floating-chip"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> &nbsp; Proses <span>cepat &amp; aman</span></div>
                </div>
            </div>
        </section>

        <section class="trust container" id="tentang"><div class="trust-card"><div class="trust-intro"><b>Tempat belajar yang bertumbuh bersama siswa.</b>Satu langkah sederhana untuk masa depan yang lebih cerah.</div><div class="trust-stat"><b>100%</b><span>Proses pendaftaran online</span></div><div class="trust-stat"><b>4 tahap</b><span>Alur pendaftaran yang jelas</span></div><div class="trust-stat"><b>24/7</b><span>Akses informasi pendaftaran</span></div></div></section>

        <section class="why"><div class="container"><span class="section-kicker">Mengapa memilih kami</span><h2 class="section-heading">Semua yang dibutuhkan untuk tumbuh, belajar, dan berprestasi.</h2><div class="benefits"><article class="benefit"><div class="benefit-icon"><i class="fa-solid fa-book-open" aria-hidden="true"></i></div><h3>Belajar dengan arah</h3><p>Lingkungan belajar yang mendukung siswa mengenali potensi dan membangun fondasi masa depan.</p></article><article class="benefit"><div class="benefit-icon"><i class="fa-solid fa-seedling" aria-hidden="true"></i></div><h3>Karakter yang kuat</h3><p>Nilai kebaikan, tanggung jawab, dan kepercayaan diri tumbuh dalam keseharian sekolah.</p></article><article class="benefit"><div class="benefit-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></div><h3>Informasi transparan</h3><p>Pantau proses pendaftaran dengan nomor pendaftaran kapan saja melalui sistem online.</p></article></div></div></section>

        <section class="cta"><div class="container"><div class="cta-box"><h2>Siap memulai perjalanan pendidikan yang lebih bermakna?</h2><p>Lengkapi pendaftaran calon siswa sekarang. Seluruh proses dapat dilakukan secara online.</p><a class="button button-primary" href="{{ route('pendaftaran.formulir') }}">Mulai Pendaftaran <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div></div></section>
    </main>

    <footer class="footer"><div class="container footer-inner"><span>© {{ now()->year }} SPMB Sekolah. Seluruh hak cipta dilindungi.</span><span>Butuh bantuan? Hubungi panitia penerimaan siswa.</span></div></footer>
@endsection
