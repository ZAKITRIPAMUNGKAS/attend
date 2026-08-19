<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Induk Siswa — {{ $schoolName }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        body {
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.35;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* ── KOP SURAT RESMI ─────────────────────────────────────────── */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 65px;
            text-align: left;
        }

        .kop-logo img {
            width: 60px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            padding-right: 65px; /* balance logo on left */
        }

        .kop-yayasan {
            font-size: 11pt;
            font-weight: bold;
            color: #0369a1;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
        }

        .kop-sekolah {
            font-size: 16pt;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 2px 0 0 0;
        }

        .kop-info {
            font-size: 8pt;
            color: #64748b;
            margin: 3px 0 0 0;
            line-height: 1.3;
        }

        .kop-divider-double {
            margin-top: 8px;
            margin-bottom: 12px;
            border-top: 2.5px solid #0284c7;
            border-bottom: 1px solid #0284c7;
            height: 3px;
        }

        /* ── DOKUMEN HEADER & INFO BOX ───────────────────────────────── */
        .doc-title-container {
            text-align: center;
            margin-bottom: 12px;
        }

        .doc-title {
            font-size: 12pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .doc-subtitle {
            font-size: 8.5pt;
            font-weight: bold;
            color: #0284c7;
            margin: 2px 0 0 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .meta-table td {
            border: none;
            padding: 4px 8px;
            font-size: 8pt;
            vertical-align: middle;
        }

        .meta-label {
            color: #64748b;
            font-weight: bold;
            width: 13%;
        }

        .meta-value {
            color: #0f172a;
            font-weight: bold;
            width: 37%;
        }

        /* ── TABEL DATA SISWA ────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 8pt;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #0284c7;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 7.8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .font-bold { font-weight: bold; }

        .badge-gender {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 7.5pt;
        }
        .badge-l { background-color: #e0f2fe; color: #0369a1; }
        .badge-p { background-color: #fce7f3; color: #be185d; }

        .badge-pass {
            background-color: #f1f5f9;
            color: #334155;
            padding: 1px 4px;
            border-radius: 3px;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            font-size: 7.5pt;
            border: 1px solid #e2e8f0;
        }

        /* ── TANDA TANGAN & PENGESAHAN ────────────────────────────────── */
        .sign-container {
            margin-top: 18px;
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .sign-container td {
            border: none;
            padding: 0;
            vertical-align: top;
            width: 50%;
            text-align: center;
            font-size: 8.5pt;
        }

        .sign-space {
            height: 50px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        .sign-role {
            font-size: 7.5pt;
            color: #64748b;
        }

        /* ── FOOTER CETAK ────────────────────────────────────────────── */
        .print-footer {
            margin-top: 14px;
            font-size: 7pt;
            color: #94a3b8;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <!-- ── KOP SURAT RESMI ─────────────────────────────────────────── -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-yayasan">YAYASAN PENDIDIKAN ISLAM INSAN KAMIL</div>
                <div class="kop-sekolah">SMA IT INSAN KAMIL KARANGANYAR</div>
                <div class="kop-info">
                    Jl. Raya Karanganyar - Matesih, Kabupaten Karanganyar, Jawa Tengah<br>
                    Website: <strong>smart.tepegrafi.id</strong> &bull; Email: <strong>info@smaitinsankamil.sch.id</strong>
                </div>
            </td>
        </tr>
    </table>

    <div class="kop-divider-double"></div>

    <!-- ── JUDUL DOKUMEN ───────────────────────────────────────────── -->
    <div class="doc-title-container">
        <h2 class="doc-title">DATA INDUK SISWA &amp; AKUN PRESENSI DIGITAL</h2>
        <div class="doc-subtitle">TAHUN AJARAN {{ strtoupper($academicYear->name ?? '2026/2027') }} &bull; SEMESTER {{ strtoupper($academicYear->semester ?? 'GANJIL') }}</div>
    </div>

    <!-- ── METADATA RINGKAS ────────────────────────────────────────── -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Rombongan Belajar:</td>
            <td class="meta-value">{{ $selectedClass ? $selectedClass->name : 'Semua Rombel (Seluruh Kelas)' }}</td>
            <td class="meta-label">Total Murid:</td>
            <td class="meta-value">{{ $totalStudents }} Murid (L: {{ $countMale }} &bull; P: {{ $countFemale }})</td>
        </tr>
        <tr>
            <td class="meta-label">Wali Kelas:</td>
            <td class="meta-value">{{ $homeroomTeacher ? $homeroomTeacher->name : 'Terlampir per Rombel' }}</td>
            <td class="meta-label">Tanggal Cetak:</td>
            <td class="meta-value">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y - H:i') }} WIB</td>
        </tr>
    </table>

    <!-- ── TABEL DATA LENGKAP ──────────────────────────────────────── -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;" class="text-center">No</th>
                <th style="width: 9%;" class="text-center">NISN</th>
                <th style="width: 7%;" class="text-center">NIS</th>
                <th style="width: 22%;">Nama Lengkap Murid</th>
                <th style="width: 5%;" class="text-center">L/P</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 13%;">Tempat, Tanggal Lahir</th>
                <th style="width: 15%;">Nama Orang Tua / Wali</th>
                <th style="width: 10%;">WhatsApp Wali</th>
                <th style="width: 6%;" class="text-center">Password</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $s)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center font-mono font-bold">{{ $s->nisn }}</td>
                    <td class="text-center font-mono">{{ $s->nis ?? '-' }}</td>
                    <td>
                        <strong style="color: #0f172a;">{{ $s->name }}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge-gender {{ $s->gender === 'L' ? 'badge-l' : 'badge-p' }}">
                            {{ $s->gender }}
                        </span>
                    </td>
                    <td>{{ $s->schoolClass->name ?? '-' }}</td>
                    <td>
                        {{ $s->birth_place ?? 'Karanganyar' }}, 
                        {{ $s->birth_date ? \Carbon\Carbon::parse($s->birth_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $s->parent_name ?? '-' }}</td>
                    <td class="font-mono" style="font-size: 7.5pt;">{{ $s->parent_phone ?? '-' }}</td>
                    <td class="text-center font-mono">
                        <span class="badge-pass">{{ $s->birth_date ? \Carbon\Carbon::parse($s->birth_date)->format('dmY') : 'password' }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data murid yang sesuai kriteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ── LEMBAR PENGESAHAN / TANDA TANGAN ────────────────────────── -->
    <table class="sign-container">
        <tr>
            <td>
                <!-- Kolom Kiri: Wali Kelas jika ada kelas spesifik -->
                @if($selectedClass && $homeroomTeacher)
                    <div>Wali Kelas {{ $selectedClass->name }}</div>
                    <div class="sign-space"></div>
                    <div class="sign-name">{{ $homeroomTeacher->name }}</div>
                    <div class="sign-role">NIP: {{ $homeroomTeacher->nip ?? '-' }}</div>
                @else
                    <div>Petugas Administrasi Kesiswaan</div>
                    <div class="sign-space"></div>
                    <div class="sign-name">Administrator Sistem</div>
                    <div class="sign-role">SmartAbsensi SMA IT Insan Kamil</div>
                @endif
            </td>
            <td>
                <!-- Kolom Kanan: Kepala Sekolah -->
                <div>Karanganyar, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div>Mengetahui,</div>
                <div style="font-weight: bold;">Kepala Sekolah SMA IT Insan Kamil</div>
                <div class="sign-space"></div>
                <div class="sign-name">Ustadz Abdullah, S.Pd</div>
                <div class="sign-role">NIP. 198501012010011001</div>
            </td>
        </tr>
    </table>

    <!-- ── PRINT FOOTER ────────────────────────────────────────────── -->
    <div class="print-footer">
        <span>Dicetak secara otomatis melalui Sistem SmartAbsensi SMA IT Insan Kamil Karanganyar &bull; https://smart.tepegrafi.id</span>
        <span style="float: right;">Halaman 1 dari 1 (Dokumen Sah)</span>
    </div>

</body>
</html>
