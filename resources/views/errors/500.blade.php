<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5">
    <title>500 — Terjadi Kesalahan Server · SmartPresensi</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:#EEF5FC;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.5rem}
        .card{background:#fff;border-radius:2rem;padding:2.5rem 2rem;max-width:400px;width:100%;text-align:center;box-shadow:0 20px 50px -10px rgba(20,70,120,.10);border:1px solid rgba(186,230,253,.6);position:relative;overflow:hidden}
        .glow{position:absolute;top:-40px;right:-40px;width:150px;height:150px;background:rgba(253,230,138,.25);border-radius:50%;filter:blur(40px);pointer-events:none}
        .glow2{position:absolute;bottom:-40px;left:-40px;width:120px;height:120px;background:rgba(253,230,138,.15);border-radius:50%;filter:blur(30px);pointer-events:none}
        .logo-wrap{display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:1.25rem;background:#F0F8FF;border:1px solid #E0F0FF;padding:8px;box-shadow:0 2px 8px rgba(30,136,229,.08);margin-bottom:1.5rem}
        .logo-wrap img{width:100%;height:100%;object-fit:contain}
        .icon-box{width:72px;height:72px;border-radius:1.5rem;background:#FFFBEB;border:1px solid #FDE68A;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}
        .code{font-size:3.5rem;font-weight:900;letter-spacing:-2px;background:linear-gradient(135deg,#F59E0B,#FBBF24);background-clip:text;-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1;margin-bottom:.5rem}
        h1{font-size:1.125rem;font-weight:900;color:#1E293B;margin-bottom:.5rem}
        p{font-size:.75rem;color:#94A3B8;font-weight:500;line-height:1.6;max-width:300px;margin:0 auto}
        .divider{width:40px;height:2px;background:linear-gradient(90deg,#F59E0B,#FBBF24);border-radius:2px;margin:1.25rem auto}
        .btn-primary{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:1.75rem;padding:.875rem 1.75rem;background:linear-gradient(135deg,#1E88E5,#42A5F5);color:#fff;font-size:.75rem;font-weight:700;border-radius:1rem;text-decoration:none;width:100%;transition:transform .15s}
        .btn-primary:hover{transform:translateY(-1px)}
        .btn-secondary{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:.625rem;padding:.75rem;background:#fff;color:#64748B;font-size:.75rem;font-weight:700;border-radius:1rem;border:1px solid #E2E8F0;text-decoration:none;width:100%}
        .btn-secondary:hover{background:#F8FAFC}
        .credit{margin-top:1.5rem;font-size:.6875rem;color:#CBD5E1;font-weight:500}
        .credit a{color:#94A3B8;text-decoration:none;font-weight:700}
        .credit a:hover{color:#1E88E5}
    </style>
</head>
<body>
    <div class="card">
        <div class="glow"></div><div class="glow2"></div>
        <div class="logo-wrap"><img src="/logo.png" alt="Logo SMA IT"></div>
        <div class="icon-box">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="1.8"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><circle cx="12" cy="17" r=".5" fill="#F59E0B"/></svg>
        </div>
        <div class="code">500</div>
        <h1>Terjadi Kesalahan Server</h1>
        <div class="divider"></div>
        <p>Server mengalami gangguan teknis. Tim kami sedang menangani masalah ini. Coba lagi beberapa saat atau hubungi administrator.</p>
        <a href="/" class="btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
            Muat Ulang Halaman
        </a>
        <a href="/" class="btn-secondary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kembali ke Beranda
        </a>
    </div>
    <div class="credit">SmartPresensi SMA IT Insan Kamil · <a href="https://tepegrafi.id" target="_blank" rel="noopener">Developed by gemala.dev</a></div>
</body>
</html>
