<x-backend-layout>
    <x-slot name="header">
        Dashboard Siswa
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

    <!-- [ Stats ] start -->
    <div class="row">
        <div class="col-xxl-6 col-md-6">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-xl rounded bg-soft-primary text-primary">
                                <i class="feather-book-open"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark">{{ $totalJournals }}</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Total Jurnal Diisi</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-md-6">
             <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-xl rounded {{ $isFilledToday ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                <i class="feather-{{ $isFilledToday ? 'check-circle' : 'clock' }}"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold text-dark">{{ $isFilledToday ? 'Sudah' : 'Belum' }}</div>
                                <h3 class="fs-13 fw-semibold text-muted mb-0">Status Hari Ini</h3>
                            </div>
                        </div>
                        @if(!$isFilledToday)
                            <a href="{{ route('journals.create') }}" class="btn btn-primary btn-sm">Isi Sekarang</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Stats ] end -->

    <!-- [ Habit Chart ] start -->
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Statistik 7 Kebiasaan Saya (Tahun Ini)</h5>
                </div>
                <div class="card-body">
                    <div id="habit-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Habit Chart ] end -->

    <!-- [ Leaderboard ] start -->
    <div class="row">
        <div class="col-12">
            @include('backend.components.leaderboard')
        </div>
    </div>
    <!-- [ Leaderboard ] end -->

    <!-- [ Recent Journal ] start -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Terkini</h5>
                    <div class="card-header-action">
                        <a href="{{ route('journals.index') }}" class="btn btn-light btn-sm">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Catatan Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentJournals as $journal)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-text bg-soft-secondary text-secondary rounded">
                                                    <i class="feather-calendar"></i>
                                                </div>
                                                <span class="fw-bold">{{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('d F Y') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($journal->status == 'menunggu')
                                                <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                            @elseif($journal->status == 'disetujui')
                                                 <span class="badge bg-soft-success text-success">Disetujui</span>
                                            @else
                                                 <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                                            @endif
                                        </td>
                                        <td>{{ $journal->catatan_guru ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada riwayat jurnal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Recent Journal ] end -->

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: [{
                    name: 'Persentase',
                    data: @json($habitPercentages)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                        distributed: true
                    }
                },
                dataLabels: { 
                    enabled: true, 
                    formatter: function (val) { return val + "%"; },
                    style: { colors: ['#fff'] }
                },
                xaxis: {
                    categories: @json($habitLabels),
                    max: 100,
                    labels: {
                        style: { cssClass: 'text-muted fill-muted' }
                    }
                },
                colors: ['#3454d1', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6610f2', '#e83e8c'],
                tooltip: {
                     y: { formatter: function (val) { return val + "%"; } }
                },
                legend: { show: false }
            };

            if(document.querySelector("#habit-chart")) {
                var chart = new ApexCharts(document.querySelector("#habit-chart"), options);
                chart.render();
            }
        });
    </script>
    @endpush
</x-backend-layout>
