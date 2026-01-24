<x-backend-layout>
    <x-slot name="header">
        Dashboard Guru
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
        <!-- Welcome & Class Info -->
        <div class="col-lg-12 mb-4">
            <div class="card bg-primary text-white stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
                            <p class="mb-0 op-8">
                                Wali Kelas: <strong>{{ $classRoom->nama_kelas ?? 'Belum ada kelas' }}</strong>
                            </p>
                        </div>
                        <div>
                            <span class="badge bg-white text-primary fw-bold fs-14 px-3 py-2">
                                <i class="feather-calendar me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($classRoom)
            <!-- Stats Cards -->
            <div class="col-xl-3 col-md-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fw-semibold mb-1">Total Siswa</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_students'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-3">
                                <i class="feather-users fs-24"></i>
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
                                <p class="text-muted fw-semibold mb-1">Jurnal Hari Ini</p>
                                <h3 class="fw-bold mb-0 text-dark">{{ $stats['journals_today'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded-3">
                                <i class="feather-book-open fs-24"></i>
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
                                <p class="text-muted fw-semibold mb-1">Perlu Pembinaan</p>
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
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fw-semibold mb-1">Menunggu Validasi</p>
                                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_review'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-3">
                                <i class="feather-clock fs-24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-12">
                <div class="card stretch stretch-full">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Jurnal Terbaru</h5>
                        <a href="{{ route('teacher.journals.index', ['date' => now()->toDateString()]) }}" class="btn btn-sm btn-light">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-top-0">Siswa</th>
                                        <th class="border-top-0 text-center">Status</th>
                                        <th class="border-top-0 text-center">Waktu</th>
                                        <th class="border-top-0 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentJournals as $journal)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar-image avatar-sm">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($journal->student->nama) }}&background=random" class="img-fluid" alt="">
                                                    </div>
                                                    <a href="{{ route('teacher.journals.show', $journal->id) }}" class="fw-bold text-dark">
                                                        {{ $journal->student->nama }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($journal->status == 'menunggu')
                                                    <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                                @elseif($journal->status == 'disetujui')
                                                    <span class="badge bg-soft-success text-success">Disetujui</span>
                                                @elseif($journal->status == 'pembinaan')
                                                    <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                                                @endif
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ \Carbon\Carbon::parse($journal->created_at)->format('H:i') }}
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('teacher.journals.show', $journal->id) }}" class="btn btn-sm btn-light">
                                                    Check
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Belum ada jurnal hari ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-12">
                <div class="alert alert-warning" role="alert">
                    Anda belum ditugaskan sebagai Wali Kelas. Hubungi Administrator.
                </div>
            </div>
        @endif
    </div>
</x-backend-layout>
