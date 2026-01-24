<x-backend-layout>
    <x-slot name="header">
        Detail Jurnal Siswa
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Jurnal Tanggal: {{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('d F Y') }}</h5>
                    @if($journal->status == 'menunggu')
                        <span class="badge bg-soft-warning text-warning">Menunggu Validasi</span>
                    @elseif($journal->status == 'disetujui')
                        <span class="badge bg-soft-success text-success">Disetujui Guru</span>
                    @elseif($journal->status == 'pembinaan')
                        <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kebiasaan</th>
                                    <th class="text-center" style="width: 15%">Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($parent->student->classRoom->school->habits ?? \App\Models\Habit::all() as $habit)
                                    @php
                                        $detail = $journal->details->where('kebiasaan', $habit->id)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($habit->icon)
                                                    <i class="{{ $habit->icon }} me-2 text-primary"></i>
                                                @endif
                                                <span class="fw-bold">{{ $habit->name }}</span>
                                            </div>
                                            <small class="text-muted">{{ $habit->description }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($detail && $detail->nilai == 1)
                                                <span class="badge bg-success"><i class="feather-check"></i> Ya</span>
                                            @else
                                                <span class="badge bg-danger"><i class="feather-x"></i> Tidak</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($detail && $detail->nilai == 0)
                                                <span class="text-danger fw-bold">Alasan:</span> {{ $detail->note ?? '-' }}
                                            @elseif($detail && $detail->nilai == 1 && $detail->actual_value)
                                                <span class="text-primary fw-bold">Waktu:</span> {{ $detail->actual_value }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Validasi Guru</h5>
                </div>
                <div class="card-body">
                   <div class="mb-4">
                        <label class="form-label text-muted">Status Validasi</label>
                        <div>
                             @if($journal->status == 'menunggu')
                                <div class="alert alert-warning">
                                    <i class="feather-clock me-1"></i> Menunggu diperiksa guru.
                                </div>
                            @elseif($journal->status == 'disetujui')
                                <div class="alert alert-success">
                                    <i class="feather-check-circle me-1"></i> Sudah disetujui guru.
                                </div>
                            @elseif($journal->status == 'pembinaan')
                                <div class="alert alert-danger">
                                    <i class="feather-alert-octagon me-1"></i> Perlu pembinaan.
                                </div>
                            @endif
                        </div>
                   </div>

                   <div class="mb-4">
                        <label class="form-label text-muted">Catatan Guru</label>
                        <div class="p-3 bg-light rounded border">
                            @if($journal->catatan_guru)
                                <p class="mb-0 text-dark">{{ $journal->catatan_guru }}</p>
                            @else
                                <p class="mb-0 text-muted fst-italic">Belum ada catatan.</p>
                            @endif
                        </div>
                   </div>

                   <!-- Parent Confirmation Section -->
                   <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Konfirmasi Orang Tua</label>
                        <div class="card border-primary border-opacity-25 bg-soft-primary">
                            <div class="card-body">
                                @if($journal->parent_confirmed_at)
                                    <div class="text-center py-2">
                                        <i class="feather-check-circle text-success fs-1 mb-2"></i>
                                        <h6 class="fw-bold mb-1">Sudah Dikonfirmasi</h6>
                                        <p class="mb-0 text-muted small">{{ $journal->parent_confirmed_at->translatedFormat('d F Y, H:i') }}</p>
                                        @if($journal->catatan_orang_tua)
                                            <hr class="border-primary border-opacity-25 my-3">
                                            <p class="text-start mb-0 fst-italic">"{{ $journal->catatan_orang_tua }}"</p>
                                        @endif
                                    </div>
                                @else
                                    <form action="{{ route('parent.journals.confirm', $journal->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label small text-muted">Catatan/Semangat (Opsional)</label>
                                            <textarea name="catatan_orang_tua" rows="2" class="form-control" placeholder="Tulis pesan semangat untuk ananda..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="feather-check me-1"></i> Konfirmasi Jurnal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                   </div>

                   <div>
                       <a href="{{ route('dashboard.orang_tua') }}" class="btn btn-secondary w-100">
                           <i class="feather-arrow-left me-1"></i> Kembali ke Dashboard
                       </a>
                   </div>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
