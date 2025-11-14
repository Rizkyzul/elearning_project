<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai - {{ $mahasiswa->user->name }}</title>
    <style>
        body { font-family: sans-serif; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .info { margin-bottom: 20px; }
        .info td { border: 0; padding: 2px; }
    </style>
</head>
<body>
    <h1>Kartu Hasil Studi (KHS)</h1>

    <table class="info">
        <tr>
            <td><strong>Nama</strong></td>
            <td>: {{ $mahasiswa->user->name }}</td>
        </tr>
        <tr>
            <td><strong>NIM</strong></td>
            <td>: {{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td><strong>Program Studi</strong></td>
            <td>: {{ $mahasiswa->prodi }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($nilaiList as $nilai)
                @php
                    $nilaiAkhir = ($nilai->nilai_tugas * 0.2) + ($nilai->nilai_uts * 0.4) + ($nilai->nilai_uas * 0.4);

                    if ($nilaiAkhir >= 80) $grade = 'A';
                    elseif ($nilaiAkhir >= 70) $grade = 'B';
                    elseif ($nilaiAkhir >= 60) $grade = 'C';
                    elseif ($nilaiAkhir >= 50) $grade = 'D';
                    else $grade = 'E';
                @endphp
                <tr>
                    <td>{{ $nilai->matkul->kode_matkul }}</td>
                    <td>{{ $nilai->matkul->nama_matkul }}</td>
                    <td>{{ $nilai->nilai_tugas ?? '-' }}</td>
                    <td>{{ $nilai->nilai_uts ?? '-' }}</td>
                    <td>{{ $nilai->nilai_uas ?? '-' }}</td>
                    <td>{{ $nilaiAkhir }}</td>
                    <td>{{ $grade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada nilai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>