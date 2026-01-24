<x-backend-layout>
    <x-slot name="header">
        Dashboard Orang Tua
    </x-slot>

    @if(isset($activeYear) && $activeYear)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <i class="feather-calendar fs-4 me-3"></i>
                    <div>
                        <strong>Tahun Ajaran Aktif:</strong> {{ $activeYear->name }} (Semester {{ $activeYear->semester }})
                        <div class="text-muted small">Periode: {{ \Carbon\Carbon::parse($activeYear->start_date)->translatedFormat('d F Y') }} s.d {{ \Carbon\Carbon::parse($activeYear->end_date)->translatedFormat('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Error Handling -->
        @if(isset($error))
        <div class="col-lg-12">
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        </div>
        @else

        <!-- Header: Student Info -->
        <div class="col-lg-12 mb-4">
            <div class="card bg-primary text-white stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-image">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama) }}&background=random" class="img-fluid rounded-circle" alt="">
                            </div>
                            <div>
                                <h4 class="text-white fw-bold mb-0">Ananda: {{ $student->nama }}</h4>
                                <p class="mb-0 op-8">Kelas: {{ $student->classRoom->nama_kelas }} | NIS: {{ $student->nis }}</p>
                            </div>
                        </div>
                        <div class="text-end d-none d-md-block">
                            <h5 class="text-white mb-0">Pantauan Orang Tua</h5>
                            <small class="op-8">{{ now()->translatedFormat('l, d F Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-semibold mb-1">Total Jurnal</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_journals'] }}</h3>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                            <i class="feather-book fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-semibold mb-1">Bulan Ini</p>
                            <h3 class="fw-bold mb-0 text-dark">{{ $stats['journals_this_month'] }}</h3>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                            <i class="feather-calendar fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted fw-semibold mb-1">Perlu Perhatian</p>
                            <h3 class="fw-bold mb-0 text-danger">{{ $stats['need_attention'] }}</h3>
                        </div>
                        <div class="avatar-text avatar-lg bg-soft-danger text-danger rounded-3">
                            <i class="feather-alert-triangle fs-24"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <!-- Motivation/Summary Card --> 
             <div class="card stretch stretch-full bg-light">
                <div class="card-body d-flex align-items-center">
                    <div>
                        <p class="mb-1 fw-bold text-dark">Dukungan Anda</p>
                        <small class="text-muted">Sangat berarti bagi pembentukan karakter ananda.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Journals -->
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Jurnal Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-top-0">Tanggal</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Catatan Guru</th>
                                    <th class="border-top-0 text-end">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJournals as $journal)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('d F Y') }}</span>
                                        </td>
                                        <td>
                                            @if($journal->status == 'menunggu')
                                                <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                            @elseif($journal->status == 'disetujui')
                                                <span class="badge bg-soft-success text-success">Disetujui</span>
                                            @elseif($journal->status == 'pembinaan')
                                                <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ Str::limit($journal->catatan_guru, 50) ?? '-' }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('parent.journals.show', $journal->id) }}" class="btn btn-sm btn-light">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Belum ada data jurnal.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-backend-layout>
