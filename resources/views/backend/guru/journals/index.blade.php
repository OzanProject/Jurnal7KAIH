<x-backend-layout>
    <x-slot name="header">
        Validasi Jurnal Siswa
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                     @if(isset($error))
                        <div class="alert alert-danger" role="alert">
                            {{ $error }}
                        </div>
                    @else
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="fw-bold mb-1">Kelas: {{ $classRoom->nama_kelas }}</h5>
                                <p class="text-muted mb-0">Total Siswa: {{ $students->count() }}</p>
                            </div>
                            
                            <form action="{{ route('teacher.journals.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                <label for="date" class="form-label mb-0">Tanggal:</label>
                                <input type="date" name="date" id="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
                            </form>
                        </div>
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Pending Validation Section -->
                        @if(isset($pendingJournals) && $pendingJournals->isNotEmpty())
                            <div class="mb-5">
                                <div class="alert alert-soft-warning mb-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="feather-alert-circle me-2"></i>
                                        <strong>{{ $pendingJournals->count() }} Jurnal Menunggu Validasi</strong> (Termasuk Backdate)
                                    </div>
                                </div>
                                <div class="table-responsive border rounded">
                                    <table class="table table-hover mb-0 bg-white">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Siswa</th>
                                                <th>Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingJournals as $pJournal)
                                                <tr>
                                                    <td>
                                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($pJournal->tanggal)->translatedFormat('d M Y') }}</span>
                                                        <div class="small text-muted">{{ \Carbon\Carbon::parse($pJournal->tanggal)->diffForHumans() }}</div>
                                                    </td>
                                                    <td>{{ $pJournal->student->nama }}</td>
                                                    <td><span class="badge bg-soft-warning text-warning">Menunggu</span></td>
                                                    <td class="text-center">
                                                        <a href="{{ route('teacher.journals.show', $pJournal->id) }}" class="btn btn-primary btn-sm">Periksa</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="my-4">
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th class="text-center">Status Jurnal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    @php
                                        $journal = $student->journals->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama) }}&background=random" alt="" class="img-fluid" />
                                                </div>
                                                <span class="fw-bold">{{ $student->nama }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $student->nis }}</td>
                                        <td class="text-center">
                                            @if($journal)
                                                @if($journal->status == 'menunggu')
                                                    <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                                @elseif($journal->status == 'disetujui')
                                                    <span class="badge bg-soft-success text-success">Disetujui</span>
                                                @elseif($journal->status == 'pembinaan')
                                                    <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                                                @endif
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary">Belum mengisi</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($journal)
                                                <a href="{{ route('teacher.journals.show', $journal->id) }}" class="btn btn-primary btn-sm">
                                                    Periksa
                                                </a>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
