<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi SMA IT Insan Kamil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #047857;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 16px;
            color: #047857;
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            font-size: 10px;
            color: #666;
            margin: 2px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #111827;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
        }
        .badge-hadir { background-color: #d1fae5; color: #065f46; }
        .badge-terlambat { background-color: #fef3c7; color: #92400e; }
        .badge-izin { background-color: #dbeafe; color: #1e40af; }
        .badge-sakit { background-color: #f3e8ff; color: #6b21a8; }
        .badge-alpa { background-color: #ffe4e6; color: #9f1239; }
        .badge-none { background-color: #f3f4f6; color: #6b7280; }
        .footer-sign {
            margin-top: 30px;
            width: 100%;
        }
        .footer-sign td {
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMA IT INSAN KAMIL</h1>
        <p>Sistem Informasi & Manajemen Kehadiran Siswa Terpadu</p>
        <p>Laporan Resmi Kehadiran Siswa — Tanggal: <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong></p>
        @if($schoolClass)
            <p>Kelas: <strong>{{ $schoolClass->name }}</strong></p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">NISN</th>
                <th style="width: 35%;">Nama Siswa</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 10%;" class="text-center">Jam</th>
                <th style="width: 15%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $idx => $s)
                @php $att = $dailyAttendances->get($s->id); @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $s->nisn }}</td>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td>{{ $s->schoolClass->name ?? '-' }}</td>
                    <td class="text-center">{{ $att && $att->check_in ? substr($att->check_in, 0, 5) : '-' }}</td>
                    <td class="text-center">
                        @if($att)
                            <span class="badge badge-{{ $att->status }}">{{ strtoupper($att->status) }}</span>
                        @else
                            <span class="badge badge-none">BELUM ABSEN</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer-sign">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                <p style="margin-top: 5px;">Kepala Sekolah / Wali Kelas</p>
                <br><br><br>
                <p><strong>( .................................................. )</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>
