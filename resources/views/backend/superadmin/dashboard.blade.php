<x-backend-layout>
    <x-slot name="header">
        Dashboard Super Admin
    </x-slot>

    <x-slot name="header">
        Dashboard Super Admin
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
                     <a href="{{ route('academic-years.index') }}" class="btn btn-sm btn-light ms-auto text-primary fw-bold">Kelola</a>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="feather-alert-triangle fs-4 me-3"></i>
                    <div>
                        <strong>Belum ada Tahun Ajaran Aktif!</strong> Harap segera tentukan tahun ajaran aktif agar sistem berjalan lancar.
                    </div>
                    <a href="{{ route('academic-years.index') }}" class="btn btn-sm btn-warning ms-auto text-dark fw-bold">Atur Sekarang</a>
                </div>
            </div>
        </div>
    @endif

    <!-- [ Stats Config ] start -->
    <div class="row">
         <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-xl rounded">
                                <i class="feather-home"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark">{{ $schoolsCount }}</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Total Sekolah</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-md-6">
             <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-xl rounded">
                                <i class="feather-users"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark">{{ $usersCount }}</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Total Users</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-md-6">
             <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-xl rounded">
                                <i class="feather-user-check"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark">{{ $studentsCount }}</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Siswa Aktif</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-md-6">
             <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-xl rounded">
                                <i class="feather-book"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark">{{ $journalsToday }}</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Jurnal Hari Ini</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Stats Config ] end -->

    <div class="row">
        <!-- Recent Activity -->
        <div class="col-xxl-8">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Aktivitas Terkini</h5>
                    <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-light-brand">Lihat Semua</a>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLogs as $log)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-text avatar-sm bg-light text-dark">
                                                {{ substr($log->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="fs-12 fw-bold text-dark">{{ $log->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary">{{ $log->action }}</span>
                                        <span class="d-block text-muted fs-11 mt-1">{{ Str::limit($log->description, 30) }}</span>
                                    </td>
                                    <td class="fs-12 text-muted">{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Belum ada aktivitas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newest Users -->
        <div class="col-xxl-4">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Pengguna Baru</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-light-brand">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($newestUsers as $user)
                        <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-text avatar-md bg-soft-info text-info">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <span class="fs-11 text-muted">{{ $user->email }}</span>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark">{{ $user->role }}</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-4">Belum ada user.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Akses Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('schools.index') }}" class="btn btn-primary w-100 py-3">
                                <i class="feather-home me-2"></i> Kelola Sekolah
                            </a>
                        </div>
                        <div class="col-md-6">
                             <a href="{{ route('users.index') }}" class="btn btn-success w-100 py-3">
                                <i class="feather-users me-2"></i> Kelola Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-backend-layout>
