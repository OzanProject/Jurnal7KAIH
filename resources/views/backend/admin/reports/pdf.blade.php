<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Rekap Jurnal Siswa</h2>
        <h3>Kelas: {{ $class->nama_kelas }}</h3>
        <p>
            Periode: 
            @if($filterType == 'semester')
                Semester {{ $selectedSemester == 1 ? 'Ganjil' : 'Genap' }} Tahun {{ $selectedYear }}
            @else
                {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th>Nama Siswa</th>
                <th class="text-center">Total Hari Mengisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td class="text-center">{{ $student->no_urut ?? $loop->iteration }}</td>
                    <td>{{ $student->nama }}</td>
                    <td class="text-center">{{ $student->journals_count }} Hari</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
