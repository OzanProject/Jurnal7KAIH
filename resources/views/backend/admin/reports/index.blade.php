<x-backend-layout>
    <x-slot name="header">
        Laporan Jurnal
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Rekap Jurnal Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ Auth::user()->role == 'guru' ? route('teacher.reports.index') : route('reports.index') }}" method="GET" class="row g-3 mb-4">
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
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="feather-filter me-1"></i> Tampilkan
                                </button>
                                @if($selectedClassId && $students->count() > 0)
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="feather-download"></i> Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><button type="submit" form="export-form" class="dropdown-item">Excel</button></li>
                                            <li><button type="submit" form="export-pdf-form" class="dropdown-item">PDF</button></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>

                    <!-- Separate Export Forms -->
                    <form id="export-form" action="{{ Auth::user()->role == 'guru' ? route('teacher.reports.export') : route('reports.export') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="filter_type" value="{{ $filterType }}">
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                        <input type="hidden" name="semester" value="{{ $selectedSemester }}">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                    </form>
                    
                     <form id="export-pdf-form" action="{{ Auth::user()->role == 'guru' ? route('teacher.reports.exportPdf') : route('reports.exportPdf') }}" method="POST" class="d-none">
                        @csrf
                        <input type="hidden" name="filter_type" value="{{ $filterType }}">
                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                        <input type="hidden" name="semester" value="{{ $selectedSemester }}">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                    </form>

                    @if($selectedClassId)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th class="text-center">Jml Hari Mengisi</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="fw-bold">{{ $student->nama }}</td>
                                            <td>{{ $student->classRoom->nama_kelas }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-soft-primary text-primary fs-12">
                                                    {{ $student->journals_count }} Hari
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($student->journals_count > 0)
                                                    <span class="badge bg-soft-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary">Belum Mengisi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="feather-inbox fs-1 text-muted"></i>
                                                <p class="text-muted mt-2">Tidak ada data siswa di kelas ini.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="feather-filter fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Silakan pilih kelas untuk melihat data.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
