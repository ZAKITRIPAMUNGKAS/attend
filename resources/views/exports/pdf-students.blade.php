<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa — {{ $schoolName }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 12mm 15mm;
        }

        * {
            box-sizing: border-box;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        body {
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.35;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* ── HEADER SIMPEL & BERSIH ───────────────────────────────────── */
        .doc-header {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #0284c7;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .doc-title {
            font-size: 14pt;
            font-weight: 900;
            color: #0f172a;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .doc-subtitle {
            font-size: 8.5pt;
            color: #0284c7;
            font-weight: bold;
            margin: 2px 0 0 0;
        }

        .header-meta {
            text-align: right;
            font-size: 8pt;
            color: #64748b;
            line-height: 1.4;
        }

        .header-meta strong {
            color: #0f172a;
        }

        /* ── INFO BOX RINGKAS ────────────────────────────────────────── */
        .meta-bar {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #f1f5f9;
            border-radius: 4px;
        }

        .meta-bar td {
            border: none;
            padding: 5px 10px;
            font-size: 8pt;
            vertical-align: middle;
        }

        /* ── TABEL DATA SISWA ────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
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

        /* ── FOOTER CETAK ────────────────────────────────────────────── */
        .print-footer {
            margin-top: 12px;
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px dashed #cbd5e1;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- ── HEADER SIMPEL ───────────────────────────────────────────── -->
    <div class="doc-header">
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="doc-title">DATA SISWA — SMA IT INSAN KAMIL</h1>
                    <p class="doc-subtitle">Tahun Ajaran {{ $academicYear->name ?? '2026/2027' }} &bull; Semester {{ ucfirst($academicYear->semester ?? 'Ganjil') }}</p>
                </td>
                <td class="header-meta">
                    <div>Kelas: <strong>{{ $selectedClass ? $selectedClass->name : 'Semua Rombel' }}</strong></div>
                    <div>Wali Kelas: <strong>{{ $homeroomTeacher ? $homeroomTeacher->name : '-' }}</strong></div>
                    <div>Dicetak: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d/m/Y H:i') }} WIB</strong></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ── INFO RINGKAS ────────────────────────────────────────────── -->
    <table class="meta-bar">
        <tr>
            <td style="width: 50%;">
                Total: <strong>{{ $totalStudents }} Murid</strong> &bull; Laki-laki: <strong>{{ $countMale }}</strong> &bull; Perempuan: <strong>{{ $countFemale }}</strong>
            </td>
            <td style="width: 50%; text-align: right; color: #64748b;">
                Sistem SmartAbsensi &bull; https://smart.tepegrafi.id
            </td>
        </tr>
    </table>

    <!-- ── TABEL DATA LENGKAP ──────────────────────────────────────── -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;" class="text-center">No</th>
                <th style="width: 9%;" class="text-center">NISN</th>
                <th style="width: 8%;" class="text-center">NIS</th>
                <th style="width: 22%;">Nama Lengkap Murid</th>
                <th style="width: 5%;" class="text-center">L/P</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 14%;">Tempat, Tanggal Lahir</th>
                <th style="width: 15%;">Nama Orang Tua / Wali</th>
                <th style="width: 8%;">No. HP Wali</th>
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
                        Tidak ada data murid yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ── PRINT FOOTER ────────────────────────────────────────────── -->
    <div class="print-footer">
        <span style="float: left;">SmartAbsensi SMA IT Insan Kamil Karanganyar</span>
        <span style="float: right;">Halaman 1 dari 1</span>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
