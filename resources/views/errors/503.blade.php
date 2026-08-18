<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5">
    <title>503 — Layanan Tidak Tersedia · SmartAbsensi</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:#EEF5FC;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:1.5rem}
        .card{background:#fff;border-radius:2rem;padding:2.5rem 2rem;max-width:400px;width:100%;text-align:center;box-shadow:0 20px 50px -10px rgba(20,70,120,.10);border:1px solid rgba(186,230,253,.6);position:relative;overflow:hidden}
        .glow{position:absolute;top:-40px;right:-40px;width:150px;height:150px;background:rgba(110,231,183,.2);border-radius:50%;filter:blur(40px);pointer-events:none}
        .glow2{position:absolute;bottom:-40px;left:-40px;width:120px;height:120px;background:rgba(110,231,183,.12);border-radius:50%;filter:blur(30px);pointer-events:none}
        .logo-wrap{display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:1.25rem;background:#F0F8FF;border:1px solid #E0F0FF;padding:8px;box-shadow:0 2px 8px rgba(30,136,229,.08);margin-bottom:1.5rem}
        .logo-wrap img{width:100%;height:100%;object-fit:contain}
        .icon-box{width:72px;height:72px;border-radius:1.5rem;background:#ECFDF5;border:1px solid #A7F3D0;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem}
        .code{font-size:3.5rem;font-weight:900;letter-spacing:-2px;background:linear-gradient(135deg,#059669,#34D399);background-clip:text;-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1;margin-bottom:.5rem}
        h1{font-size:1.125rem;font-weight:900;color:#1E293B;margin-bottom:.5rem}
        p{font-size:.75rem;color:#94A3B8;font-weight:500;line-height:1.6;max-width:300px;margin:0 auto}
        .divider{width:40px;height:2px;background:linear-gradient(90deg,#059669,#34D399);border-radius:2px;margin:1.25rem auto}
        .btn-primary{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:1.75rem;padding:.875rem 1.75rem;background:linear-gradient(135deg,#1E88E5,#42A5F5);color:#fff;font-size:.75rem;font-weight:700;border-radius:1rem;text-decoration:none;width:100%;transition:transform .15s}
        .btn-primary:hover{transform:translateY(-1px)}
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
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.8"><path d="M12 22C6.5 22 2 17.5 2 12S6.5 2 12 2s10 4.5 10 10"/><path d="M12 6v6l4 2"/><path d="m16 16 2 2 4-4"/></svg>
        </div>
        <div class="code">503</div>
        <h1>Sedang Dalam Pemeliharaan</h1>
        <div class="divider"></div>
        <p>SmartAbsensi sedang dalam proses pemeliharaan dan peningkatan sistem. Kami akan kembali segera. Terima kasih atas kesabarannya!</p>
        <a href="javascript:location.reload()" class="btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Coba Lagi
        </a>
    </div>
    <div class="credit">SmartAbsensi SMAIT · <a href="https://tepegrafi.id" target="_blank" rel="noopener">Developed by gemala.dev</a></div>
</body>
</html>
