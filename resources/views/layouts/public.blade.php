<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPMB Sekolah')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" referrerpolicy="no-referrer">
    <style>
        :root {
            --brand: #34cd34;
            --brand-dark: #158d2a;
            --brand-deep: #075822;
            --accent: #f5ff16;
            --ink: #14261a;
            --muted: #5f7165;
            --canvas: #f7fff4;
            --line: #dcebdc;
            --white: #ffffff;
            --shadow: 0 18px 50px rgba(10, 84, 33, .12);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; color: var(--ink); background: var(--canvas); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; }
        .container { width: min(1180px, calc(100% - 48px)); margin: 0 auto; }
        .button { display: inline-flex; align-items: center; justify-content: center; gap: 9px; padding: 13px 20px; border: 1px solid transparent; border-radius: 999px; font-weight: 750; text-decoration: none; transition: transform .2s, box-shadow .2s, background .2s; }
        .button:hover { transform: translateY(-2px); }
        .button-primary { color: #12320e; background: var(--accent); box-shadow: 0 10px 22px rgba(216, 224, 0, .26); }
        .button-secondary { color: var(--white); border-color: rgba(255, 255, 255, .45); background: rgba(255, 255, 255, .10); }
        @yield('styles')
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
