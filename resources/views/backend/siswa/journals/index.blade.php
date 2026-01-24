<x-backend-layout>
    <x-slot name="header">
        Riwayat Jurnal Harian
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <!-- Header with Filters -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">Jurnal Saya</h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <form action="{{ route('journals.index') }}" method="GET" class="d-flex align-items-center gap-2">
                            <select name="month" class="form-select form-select-sm" style="width: 130px;">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="form-select form-select-sm" style="width: 100px;">
                                <option value="">Semua Tahun</option>
                                @foreach(range(date('Y'), date('Y')-2) as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-soft-primary">
                                <i class="feather-filter"></i>
                            </button>
                        </form>
                        <a href="{{ route('journals.create') }}" class="btn btn-sm btn-success">
                            <i class="feather-plus me-1"></i> Isi Jurnal
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                            {{ session('error') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pencapaian Kebiasaan</th>
                                    <th>Catatan Guru</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($journals as $journal)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text bg-soft-primary text-primary rounded">
                                                <i class="feather-calendar"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block text-dark">{{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('d F Y') }}</span>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('l') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width: 30%;">
                                        @php
                                            $totalHabits = 7;
                                            $completed = $journal->habits_count ?? 0;
                                            $percentage = ($completed / $totalHabits) * 100;
                                            $color = $percentage == 100 ? 'success' : ($percentage >= 50 ? 'primary' : 'warning');
                                        @endphp
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fs-12 fw-medium text-dark">{{ $completed }}/{{ $totalHabits }} Kebiasaan</span>
                                            <span class="fs-12 fw-medium text-{{ $color }}">{{ round($percentage) }}%</span>
                                        </div>
                                        <div class="progress ht-5">
                                            <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($journal->catatan_guru)
                                            <span class="text-dark"><i class="feather-message-square me-1 text-warning"></i> {{ Str::limit($journal->catatan_guru, 40) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($journal->status == 'menunggu')
                                            <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                        @elseif($journal->status == 'disetujui')
                                            <span class="badge bg-soft-success text-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">Pembinaan</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('journals.show', $journal->id) }}" class="btn btn-sm btn-icon btn-soft-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                                <i class="feather-eye"></i>
                                            </a>
                                            {{-- Always show delete button --}}
                                            <form action="{{ route('journals.destroy', $journal->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-soft-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jurnal ini?')" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="avatar-text avatar-xl bg-soft-light text-muted rounded-circle mb-3">
                                                <i class="feather-inbox fs-2"></i>
                                            </div>
                                            <h5 class="text-muted">Belum ada jurnal yang ditemukan.</h5>
                                            <p class="text-muted fs-13 mb-3">Mulai isi jurnal harianmu sekarang!</p>
                                            <a href="{{ route('journals.create') }}" class="btn btn-sm btn-primary">
                                                <i class="feather-edit-3 me-1"></i> Isi Jurnal
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0">
                    {{ $journals->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
