<!DOCTYPE html>
<html>
<head>
    <title>Rapor Karakter Siswa</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 80px; height: auto; position: absolute; left: 0; top: 0; }
        .school-name { font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .school-address { font-size: 10pt; margin: 2px 0; }
        .document-title { text-align: center; font-size: 14pt; font-weight: bold; margin: 20px 0; text-decoration: underline; text-transform: uppercase; }
        
        .biodata-table { width: 100%; margin-bottom: 20px; }
        .biodata-table td { padding: 4px; vertical-align: top; }
        .label { width: 150px; font-weight: bold; }
        
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 8px; }
        .content-table th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        
        .footer-section { width: 100%; margin-top: 50px; }
        .signature-box { width: 33%; float: left; text-align: center; }
        .signature-space { height: 80px; }
        
        .page-break { page-break-after: always; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 60pt; color: rgba(0,0,0,0.05); z-index: -1; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        @if($student->user->school->logo)
            <img src="{{ public_path('storage/' . $student->user->school->logo) }}" class="logo">
        @else
            <img src="{{ public_path('template-admin/assets/images/logo-abbr.png') }}" class="logo">
        @endif
        
        <h2 class="school-name">{{ $student->user->school->nama_sekolah }}</h2>
        <p class="school-address">{{ $student->user->school->alamat ?? 'Alamat Sekolah belum diatur' }}</p>
        <p class="school-address">Email: {{ $student->user->school->email ?? '-' }} | Telp: {{ $student->user->school->no_telp ?? '-' }}</p>
    </div>

    <h3 class="document-title">RAPOR PENGUATAN PENDIDIKAN KARAKTER<br>(7 KEBIASAAN ANAK HEBAT)</h3>

    <table class="biodata-table">
        <tr>
            <td class="label">Nama Peserta Didik</td>
            <td>: {{ $student->nama }}</td>
            <td class="label">Tahun Ajaran</td>
            <td>: {{ $activeYear->name ?? date('Y') }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Induk / NISN</td>
            <td>: {{ $student->nis }} / {{ $student->nisn ?? '-' }}</td>
            <td class="label">Semester</td>
            <td>: {{ $activeYear->semester ?? 1 }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $student->classRoom->nama_kelas ?? '-' }}</td>
            <td class="label">Wali Kelas</td>
            <td>: {{ $student->classRoom->teacher->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="200">Aspek Kebiasaan</th>
                <th width="80">Konsistensi (%)</th>
                <th width="100">Predikat</th>
                <th>Catatan Wali Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $index => $stat)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-bold">{{ $stat['habit']->name }}</td>
                <td class="text-center">{{ $stat['percentage'] }}%</td>
                <td class="text-center">
                    @if($stat['predikat'] == 'Sangat Baik')
                        <span style="font-weight:bold;">A (Sangat Baik)</span>
                    @elseif($stat['predikat'] == 'Baik')
                        <span>B (Baik)</span>
                    @elseif($stat['predikat'] == 'Cukup')
                        <span>C (Cukup)</span>
                    @else
                        <span style="font-style:italic;">D (Perlu Bimbingan)</span>
                    @endif
                </td>
                <td>{{ $stat['description'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-bottom: 20px;">
        <strong>Keterangan Predikat:</strong><br>
        A (Sangat Baik): 80% - 100% | B (Baik): 70% - 79% | C (Cukup): 50% - 69% | D (Perlu Bimbingan): < 50%
    </div>

    <div class="footer-section">
        <div class="signature-box">
            <br>
            Mengetahui,<br>
            Orang Tua / Wali,
            <div class="signature-space"></div>
            (.......................................)
        </div>
        
        <div class="signature-box">
            <br>
            <br>
            Kepala Sekolah,
            <div class="signature-space"></div>
            <strong><u>{{ $student->user->school->kepala_sekolah ?? '..............................' }}</u></strong><br>
            NIP. {{ $student->user->school->nip_kepala_sekolah ?? '-' }}
        </div>

        <div class="signature-box">
            {{ \App\Models\Setting::get('city_name', 'Jakarta') }}, {{ date('d F Y') }}<br>
            Wali Kelas,
            <div class="signature-space"></div>
            <strong><u>{{ $student->classRoom->teacher->name ?? '..............................' }}</u></strong><br>
            NIP. -
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <div class="watermark">7 HEADER</div>

</body>
</html>
