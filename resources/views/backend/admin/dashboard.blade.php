<x-backend-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="fw-bold mb-0">Dashboard</h4>
            <span class="text-muted d-none d-md-inline">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </x-slot>

    <!-- [ Welcome Banner ] start -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 overflow-hidden" style="background: linear-gradient(135deg, var(--bs-primary), #00308F);">
                <div class="card-body position-relative p-4">
                    <div class="d-flex align-items-center justify-content-between position-relative z-1">
                        <div>
                            <h3 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
                            <p class="mb-0 opacity-75">
                                @if(isset($activeYear) && $activeYear)
                                    Tahun Ajaran: <strong>{{ $activeYear->name }}</strong> (Semester {{ $activeYear->semester }})
                                @else
                                    Pantau aktivitas sekolah dan perkembangan karakter siswa hari ini.
                                @endif
                            </p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="feather-sun display-4 opacity-50"></i>
                        </div>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="position-absolute top-0 end-0 p-5 rounded-circle bg-white opacity-10" style="margin-top: -50px; margin-right: -50px;"></div>
                    <div class="position-absolute bottom-0 start-0 p-4 rounded-circle bg-white opacity-10" style="margin-bottom: -30px; margin-left: -30px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Welcome Banner ] end -->

    <!-- [ Stats Config ] start -->
    <div class="row g-3 mb-4">
        <!-- Total Siswa -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="avatar-text avatar-lg rounded bg-soft-primary text-primary">
                            <i class="feather-users"></i>
                        </div>
                        <span class="badge bg-soft-success text-success">+{{ $studentsCount }} Total</span>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $studentsCount }}</h3>
                        <span class="fs-12 text-muted fw-medium text-uppercase spacing-1">Total Siswa</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Guru -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="avatar-text avatar-lg rounded bg-soft-info text-info">
                            <i class="feather-briefcase"></i>
                        </div>
                         <span class="badge bg-soft-info text-info">{{ $teachersCount }} Aktif</span>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $teachersCount }}</h3>
                        <span class="fs-12 text-muted fw-medium text-uppercase spacing-1">Total Guru</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="avatar-text avatar-lg rounded bg-soft-warning text-warning">
                            <i class="feather-layers"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $classesCount }}</h3>
                        <span class="fs-12 text-muted fw-medium text-uppercase spacing-1">Total Kelas</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jurnal Hari Ini -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                         <div class="avatar-text avatar-lg rounded bg-soft-success text-success">
                            <i class="feather-check-circle"></i>
                        </div>
                        <span class="badge bg-soft-primary text-primary">{{ date('d M') }}</span>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $journalsToday }}</h3>
                        <span class="fs-12 text-muted fw-medium text-uppercase spacing-1">Jurnal Terisi Hari Ini</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Stats Config ] end -->

    <!-- [ Charts ] start -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card stretch stretch-full h-100 border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Partisipasi Jurnal</h5>
                        <p class="text-muted fs-12 mb-0">Tren pengisian 14 hari terakhir</p>
                    </div>
                    <i class="feather-bar-chart-2 text-muted fs-4"></i>
                </div>
                <div class="card-body pt-2">
                    <div id="journal-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card stretch stretch-full h-100 border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-0">
                    <h5 class="card-title fw-bold mb-1">Capaian Kebiasaan</h5>
                    <p class="text-muted fs-12 mb-0">Rata-rata pelaksanaan per aspek</p>
                </div>
                <div class="card-body pt-2">
                    <div id="habit-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Charts ] end -->

    <!-- [ Leaderboard ] start -->
    <div class="row">
        <div class="col-12">
            @include('backend.components.leaderboard')
        </div>
    </div>
    <!-- [ Leaderboard ] end -->

    <!-- [ Recent Activity ] start -->
    <div class="row">
        <!-- Aktivitas Jurnal -->
        <div class="col-lg-8 mb-4 mb-lg-0">
             <div class="card stretch stretch-full h-100 border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">Aktivitas Jurnal Terbaru</h5>
                    <a href="{{ route('journals.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Siswa</th>
                                    <th>Kelas</th>
                                    <th>Waktu</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJournals as $journal)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                 <div class="avatar-image avatar-sm">
                                                     @if($journal->student->user->avatar)
                                                        <img src="{{ asset('storage/' . $journal->student->user->avatar) }}" alt="" class="img-fluid rounded-circle" />
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($journal->student->nama) }}&background=random" alt="" class="img-fluid rounded-circle" />
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block text-dark">{{ $journal->student->nama }}</span>
                                                    <small class="text-muted">{{ $journal->student->nis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-secondary text-secondary">{{ $journal->student->classRoom->nama_kelas ?? '-' }}</span>
                                        </td>
                                        <td class="text-muted fs-12">{{ $journal->created_at->diffForHumans() }}</td>
                                        <td class="text-end pe-4">
                                            @if($journal->status == 'menunggu')
                                                <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                            @elseif($journal->status == 'disetujui')
                                                 <span class="badge bg-soft-success text-success">Disetujui</span>
                                            @else
                                                 <span class="badge bg-soft-danger text-danger">Pembinaan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="feather-inbox fs-1 text-muted opacity-50"></i>
                                            <p class="text-muted mb-0 mt-2">Belum ada aktivitas jurnal hari ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Sistem -->
        <div class="col-lg-4">
             <div class="card stretch stretch-full h-100 border-0 shadow-sm">
                <div class="card-header border-bottom-0 pb-2">
                    <h5 class="card-title fw-bold">Log Sistem</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                         <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @forelse($recentLogs as $log)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                 <div class="avatar-text avatar-sm bg-soft-primary text-primary rounded-circle fw-bold">
                                                     {{ substr($log->user->name ?? 'S', 0, 1) }}
                                                 </div>
                                                <div class="overflow-hidden">
                                                    <span class="fs-13 fw-bold d-block text-truncate" style="max-width: 150px;">{{ $log->user->name ?? 'System' }}</span>
                                                    <span class="text-muted fs-11 text-truncate d-block">{{ $log->action }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="text-muted fs-11">{{ $log->created_at->diffForHumans(null, true) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center text-muted py-4">Belum ada log.</td></tr>
                                @endforelse
                            </tbody>
                         </table>
                    </div>
                </div>
             </div>
        </div>
    </div>
    <!-- [ Recent Activity ] end -->

    @push('scripts')
    <script>
        // Journal Progress Chart (Line Chart)
        var optionsJournal = {
            series: [{
                name: "Jumlah Jurnal",
                data: @json($counts) 
            }],
            chart: {
                height: 300,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: @json($dates),
                tooltip: { enabled: false },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                tickAmount: 5,
            },
            grid: {
                strokeDashArray: 4,
                borderColor: '#e0e6ed',
            },
            colors: ['#0B5ED7'], // Kemendikbud Blue
            tooltip: {
                theme: 'light',
                x: { show: true },
            }
        };

        var chartJournal = new ApexCharts(document.querySelector("#journal-chart"), optionsJournal);
        chartJournal.render();

        // Habit Percentage Chart (Bar)
        var optionsHabit = {
            series: [{
                name: 'Persentase Ya',
                data: @json($habitStats)
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: { 
                enabled: true, 
                formatter: function (val) { return val + "%"; },
                style: { fontSize: '10px' },
                offsetX: 10
            },
            xaxis: {
                categories: ['Bangun Pagi', 'Beribadah', 'Berolahraga', 'Makan Sehat', 'Gemar Belajar', 'Bermasyarakat', 'Tidur Cepat'],
                max: 100,
            },
            colors: ['#198754'], // Success Green
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        var chartHabit = new ApexCharts(document.querySelector("#habit-chart"), optionsHabit);
        chartHabit.render();
    </script>
    @endpush
</x-backend-layout>
