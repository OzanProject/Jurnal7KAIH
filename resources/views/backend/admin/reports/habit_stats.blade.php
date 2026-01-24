<x-backend-layout>
    <x-slot name="header">
        Laporan Statistik Pembiasaan
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Statistik Pencapaian Pembiasaan</h5>
                </div>
                <div class="card-body">
                    <!-- Filter -->
                    <form action="{{ Auth::user()->role == 'guru' ? route('teacher.reports.habitStats') : route('reports.habitStats') }}" method="GET" class="row g-3 mb-4">
                        <div class="col-md-2">
                             <label for="filter_type" class="form-label">Tipe Filter</label>
                             <select name="filter_type" id="filter_type" class="form-select" onchange="this.form.submit()">
                                 <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Bulanan</option>
                                 <option value="semester" {{ $filterType == 'semester' ? 'selected' : '' }}>Semester</option>
                             </select>
                        </div>
                        
                        @if($filterType == 'month')
                        <div class="col-md-2">
                            <label for="month" class="form-label">Bulan</label>
                            <select name="month" id="month" class="form-select">
                                @foreach($months as $key => $val)
                                    <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="col-md-2">
                            <label for="semester" class="form-label">Semester</label>
                            <select name="semester" id="semester" class="form-select">
                                <option value="1" {{ $selectedSemester == 1 ? 'selected' : '' }}>Ganjil (Jul-Des)</option>
                                <option value="2" {{ $selectedSemester == 2 ? 'selected' : '' }}>Genap (Jan-Jun)</option>
                            </select>
                        </div>
                        @endif

                        <div class="col-md-2">
                            <label for="year" class="form-label">Tahun</label>
                            <select name="year" id="year" class="form-select">
                                @for($y = date('Y'); $y >= 2024; $y--)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="class_id" class="form-label">Kelas</label>
                            <select name="class_id" id="class_id" class="form-select">
                                <option value="" disabled selected>Pilih Kelas</option>
                                @foreach($classRooms as $class)
                                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="feather-filter me-1"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                    
                    @if($selectedClassId)
                        <!-- Helper -->
                        @php
                            function calcPerc($val, $total) {
                                return $total > 0 ? round(($val / $total) * 100) . '%' : '0%';
                            }
                        @endphp

                        <!-- TABLE 1: Aggregate Status -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3">1. Rekapitulasi Umum (Semua Siswa)</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr class="align-middle text-center bg-light">
                                            <th rowspan="2" style="width: 50px;">No</th>
                                            <th rowspan="2" class="text-start">Pembiasaan</th>
                                            <th colspan="3" class="fw-bold">Status</th>
                                        </tr>
                                        <tr class="align-middle text-center bg-light">
                                            <th class="bg-success text-white">Sudah Terbiasa</th>
                                            <th class="bg-warning text-dark">Cukup Terbiasa</th>
                                            <th class="bg-danger text-white">Belum Terbiasa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats as $id => $stat)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-bold">
                                                 @if(!empty($stat['icon'])) <i class="{{ $stat['icon'] }} me-2 text-primary"></i> @endif {{ $stat['name'] }}
                                            </td>
                                            <td class="text-center bg-soft-success">
                                                 <span class="fw-bold">{{ $stat['total']['sudah'] }}</span> <span class="text-muted small">({{ calcPerc($stat['total']['sudah'], $stat['total']['count']) }})</span>
                                            </td>
                                            <td class="text-center bg-soft-warning">
                                                 <span class="fw-bold">{{ $stat['total']['cukup'] }}</span> <span class="text-muted small">({{ calcPerc($stat['total']['cukup'], $stat['total']['count']) }})</span>
                                            </td>
                                            <td class="text-center bg-soft-danger">
                                                 <span class="fw-bold">{{ $stat['total']['belum'] }}</span> <span class="text-muted small">({{ calcPerc($stat['total']['belum'], $stat['total']['count']) }})</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TABLE 2: By Gender -->
                        <div class="mb-5">
                             <h6 class="fw-bold mb-3">2. Rekapitulasi Berdasarkan Jenis Kelamin</h6>
                             <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr class="align-middle">
                                            <th rowspan="2" class="text-center bg-light" style="width: 50px;">No</th>
                                            <th rowspan="2" class="bg-light">Pembiasaan</th>
                                            <th colspan="3" class="text-center" style="background-color: #fce4ec;">Perempuan</th>
                                            <th colspan="3" class="text-center" style="background-color: #e3f2fd;">Laki-laki</th>
                                        </tr>
                                        <tr class="align-middle text-center">
                                            <!-- Perempuan Headers -->
                                            <th style="background-color: #f8bbd0;">Sudah Terbiasa</th>
                                            <th style="background-color: #f8bbd0;">Cukup Terbiasa</th>
                                            <th style="background-color: #f8bbd0;">Belum Terbiasa</th>
                                            
                                            <!-- Laki-laki Headers -->
                                            <th style="background-color: #bbdefb;">Sudah Terbiasa</th>
                                            <th style="background-color: #bbdefb;">Cukup Terbiasa</th>
                                            <th style="background-color: #bbdefb;">Belum Terbiasa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats as $id => $stat)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if(!empty($stat['icon']))
                                                            <i class="{{ $stat['icon'] }} me-2 text-primary fs-5"></i>
                                                        @endif
                                                        <span class="fw-bold text-dark">{{ $stat['name'] }}</span>
                                                    </div>
                                                </td>
                                                
                                                <!-- Perempuan Data -->
                                                <td class="text-center" style="background-color: #fce4ec;">
                                                    <span class="fw-bold">{{ $stat['gender']['P']['sudah'] }}</span>
                                                </td>
                                                <td class="text-center" style="background-color: #fce4ec;">
                                                    <span class="fw-bold">{{ $stat['gender']['P']['cukup'] }}</span>
                                                </td>
                                                <td class="text-center" style="background-color: #fce4ec;">
                                                    <span class="fw-bold">{{ $stat['gender']['P']['belum'] }}</span>
                                                </td>

                                                <!-- Laki-laki Data -->
                                                <td class="text-center" style="background-color: #e3f2fd;">
                                                    <span class="fw-bold">{{ $stat['gender']['L']['sudah'] }}</span>
                                                </td>
                                                <td class="text-center" style="background-color: #e3f2fd;">
                                                    <span class="fw-bold">{{ $stat['gender']['L']['cukup'] }}</span>
                                                </td>
                                                <td class="text-center" style="background-color: #e3f2fd;">
                                                    <span class="fw-bold">{{ $stat['gender']['L']['belum'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                             </div>
                        </div>

                        <!-- TABLE 3: Student Status Summary -->
                        <div>
                             <h6 class="fw-bold mb-3">3. Rekapitulasi Status Siswa (Secara Keseluruhan)</h6>
                             <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="bg-light">
                                        <tr class="align-middle text-center">
                                            <th rowspan="2" style="width: 50px;">No</th>
                                            <th rowspan="2">Jenis Kelamin</th>
                                            <th rowspan="2" style="width: 100px;">Jml Siswa</th>
                                            <th colspan="3">Status</th>
                                        </tr>
                                        <tr class="align-middle text-center">
                                            <th class="bg-success text-white">Terbiasa</th>
                                            <th class="bg-warning text-dark">Cukup Terbiasa</th>
                                            <th class="bg-danger text-white">Belum Terbiasa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>Perempuan</td>
                                            <td class="text-center fw-bold">{{ $genderSummary['P']['count'] }}</td>
                                            <td class="text-center bg-soft-success fw-bold">{{ $genderSummary['P']['sudah'] }}</td>
                                            <td class="text-center bg-soft-warning fw-bold">{{ $genderSummary['P']['cukup'] }}</td>
                                            <td class="text-center bg-soft-danger fw-bold">{{ $genderSummary['P']['belum'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td>Laki-laki</td>
                                            <td class="text-center fw-bold">{{ $genderSummary['L']['count'] }}</td>
                                            <td class="text-center bg-soft-success fw-bold">{{ $genderSummary['L']['sudah'] }}</td>
                                            <td class="text-center bg-soft-warning fw-bold">{{ $genderSummary['L']['cukup'] }}</td>
                                            <td class="text-center bg-soft-danger fw-bold">{{ $genderSummary['L']['belum'] }}</td>
                                        </tr>
                                        <tr class="fw-bold bg-light">
                                            <td colspan="2" class="text-end">Total</td>
                                            <td class="text-center">{{ $genderSummary['P']['count'] + $genderSummary['L']['count'] }}</td>
                                            <td class="text-center">{{ $genderSummary['P']['sudah'] + $genderSummary['L']['sudah'] }}</td>
                                            <td class="text-center">{{ $genderSummary['P']['cukup'] + $genderSummary['L']['cukup'] }}</td>
                                            <td class="text-center">{{ $genderSummary['P']['belum'] + $genderSummary['L']['belum'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                             </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="feather-pie-chart fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Silakan pilih kelas untuk melihat statistik pembiasaan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
