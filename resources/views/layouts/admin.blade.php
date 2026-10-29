<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin') — SPMB Sekolah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" referrerpolicy="no-referrer">
    <style>
        :root { --brand:#34cd34; --brand-dark:#158d2a; --brand-deep:#075822; --accent:#f5ff16; --ink:#16251b; --muted:#708077; --line:#e2eee2; --canvas:#f6fbf4; --card:#fff; --sidebar:#103e23; --radius:18px; --shadow:0 10px 28px rgba(18,91,40,.08); }
        * { box-sizing:border-box; } body { margin:0; color:var(--ink); background:var(--canvas); font:14px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif; } button,input,select { font:inherit; } a { color:inherit; }
        .shell { display:flex; min-height:100vh; }
        .sidebar { position:sticky; top:0; width:264px; height:100vh; display:flex; flex:none; flex-direction:column; padding:22px 14px 16px; color:#d4e6d8; background:linear-gradient(180deg,#103e23,#092c19); }
        .brand { display:flex; align-items:center; gap:11px; padding:3px 10px 28px; color:#fff; font-weight:850; letter-spacing:-.02em; text-decoration:none; }
        .brand-mark { display:grid; width:39px; height:39px; place-items:center; border-radius:12px; color:var(--brand-deep); background:var(--accent); box-shadow:0 8px 16px rgba(0,0,0,.18); }
        .brand small { display:block; margin-top:1px; color:#9fc5a8; font-size:10px; font-weight:650; letter-spacing:.04em; text-transform:uppercase; }
        .nav-label { margin:6px 10px 8px; color:#8ab597; font-size:10px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; }
        .nav-item { display:flex; align-items:center; gap:12px; margin:2px 0; padding:11px 12px; border-radius:11px; color:#cae0d0; font-size:13px; font-weight:650; text-decoration:none; transition:.2s; }
        .nav-item i { width:18px; text-align:center; font-size:15px; }.nav-item:hover { color:#fff; background:rgba(255,255,255,.08); }.nav-item.active { color:#123e1a; background:var(--accent); box-shadow:0 8px 18px rgba(0,0,0,.13); }.nav-item.muted { opacity:.6; cursor:default; }.nav-item.muted:hover { background:transparent; color:#cae0d0; }
        .sidebar-bottom { margin-top:auto; padding-top:17px; border-top:1px solid rgba(255,255,255,.12); }.logout button { width:100%; padding:10px 12px; border:0; border-radius:10px; cursor:pointer; color:#d4e6d8; background:transparent; text-align:left; font-weight:650; }.logout button:hover { background:rgba(255,255,255,.08); }
        .workspace { min-width:0; flex:1; }.topbar { height:76px; display:flex; align-items:center; justify-content:space-between; padding:0 34px; border-bottom:1px solid var(--line); background:rgba(255,255,255,.9); }.topbar-path { color:var(--muted); font-size:13px; }.topbar-path b { color:var(--ink); }.account { display:flex; align-items:center; gap:10px; }.avatar { display:grid; width:38px; height:38px; place-items:center; border:2px solid #d8f2da; border-radius:50%; color:#fff; background:linear-gradient(135deg,var(--brand),var(--brand-dark)); font-size:14px; font-weight:800; }.account-text strong { display:block; font-size:13px; }.account-text span { color:var(--muted); font-size:11px; }
        .main { max-width:1550px; padding:30px 34px 42px; }.page-heading { display:flex; justify-content:space-between; align-items:flex-start; gap:18px; margin-bottom:25px; }.page-title { margin:0; font-size:29px; letter-spacing:-.045em; }.subtitle { margin:5px 0 0; color:var(--muted); font-size:13px; }.card { border:1px solid var(--line); border-radius:var(--radius); background:var(--card); box-shadow:var(--shadow); }.panel { padding:19px; }.btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-height:38px; padding:8px 13px; border:1px solid #cfe0d0; border-radius:10px; cursor:pointer; color:#26412e; background:#fff; font-size:12px; font-weight:750; text-decoration:none; transition:.2s; }.btn:hover { border-color:var(--brand); transform:translateY(-1px); }.btn-primary { border-color:var(--brand-dark); color:#fff; background:var(--brand-dark); box-shadow:0 7px 16px rgba(21,141,42,.16); }.btn-primary:hover { background:var(--brand-deep); }
        .table-wrap { overflow-x:auto; } table { width:100%; border-collapse:collapse; font-size:12px; } th { padding:11px 10px; color:#63756a; background:#f6fbf5; font-size:11px; font-weight:800; text-align:left; text-transform:uppercase; letter-spacing:.025em; } td { padding:12px 10px; border-top:1px solid #edf3ec; white-space:nowrap; } tbody tr:hover { background:#fbfefb; }.empty { padding:30px; color:var(--muted); text-align:center; }.badge { display:inline-flex; align-items:center; padding:4px 9px; border-radius:99px; font-size:10px; font-weight:800; }.badge.menunggu_dokumen,.badge.menunggu_verifikasi_pembayaran,.badge.menunggu_verifikasi{color:#996100;background:#fff3d4}.badge.menunggu_pembayaran,.badge.siap_keputusan{color:#21649e;background:#e8f3ff}.badge.diterima{color:#087441;background:#ddf7e7}.badge.perlu_perbaikan_dokumen,.badge.perlu_perbaikan_pembayaran,.badge.perlu_perbaikan{color:#b45a00;background:#fff0df}.badge.ditolak{color:#b92d2d;background:#ffe5e5}
        @media(max-width:850px){ .sidebar{width:76px;padding:18px 10px}.brand{justify-content:center;padding:3px 0 28px}.brand>span:last-child,.nav-label,.nav-item span,.account-text,.topbar-path{display:none}.nav-item{justify-content:center;padding:12px}.nav-item i{width:auto}.main{padding:24px}.topbar{padding:0 24px} }.@media(max-width:560px){.shell{display:block}.sidebar{position:static;width:100%;height:auto;flex-direction:row;overflow-x:auto;padding:10px}.brand{padding:0 8px}.sidebar-bottom{display:none}.nav-item{flex:none}.main{padding:20px 15px}.topbar{height:64px;padding:0 15px}.page-title{font-size:25px}}
        @stack('styles')
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.home') }}"><span class="brand-mark"><i class="fa-solid fa-graduation-cap"></i></span><span>SPMB Sekolah<small>Panel Administrasi</small></span></a>
        <div class="nav-label">Menu utama</div>
        <a class="nav-item {{ request()->routeIs('admin.home') ? 'active' : '' }}" href="{{ route('admin.home') }}"><i class="fa-solid fa-grid-2"></i><span>Dashboard</span></a>
        <a class="nav-item {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}" href="{{ route('admin.pendaftaran.index') }}"><i class="fa-solid fa-users"></i><span>Data Pendaftaran</span></a>
        <a class="nav-item {{ request()->routeIs('admin.verifikasi-dokumen.*') ? 'active' : '' }}" href="{{ route('admin.verifikasi-dokumen.index') }}"><i class="fa-solid fa-file-circle-check"></i><span>Verifikasi Dokumen</span></a>
        <a class="nav-item {{ request()->routeIs('admin.verifikasi-pembayaran.*') ? 'active' : '' }}" href="{{ route('admin.verifikasi-pembayaran.index') }}"><i class="fa-solid fa-credit-card"></i><span>Verifikasi Pembayaran</span></a>
        <a class="nav-item {{ request()->routeIs('admin.daftar-diterima.*') ? 'active' : '' }}" href="{{ route('admin.daftar-diterima.index') }}"><i class="fa-solid fa-user-check"></i><span>Daftar Diterima</span></a>
        <a class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}"><i class="fa-solid fa-chart-column"></i><span>Laporan</span></a>
        <a class="nav-item muted" href="#" title="Akan dibuat pada tahap berikutnya"><i class="fa-solid fa-gear"></i><span>Pengaturan</span></a>
        <div class="sidebar-bottom logout"><form method="POST" action="{{ route('admin.logout') }}">@csrf<button><i class="fa-solid fa-arrow-right-from-bracket"></i>&nbsp;&nbsp; Keluar</button></form></div>
    </aside>
    <div class="workspace">
        <header class="topbar"><div class="topbar-path"><b>Admin</b> &nbsp;/&nbsp; @yield('title', 'Dashboard')</div><div class="account"><div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><div class="account-text"><strong>{{ auth()->user()->name }}</strong><span>Administrator</span></div></div></header>
        <main class="main">@yield('content')</main>
    </div>
</div>
</body></html>
