<x-backend-layout>
    <x-slot name="header">
        Validasi Jurnal
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">{{ $journal->student->nama }} ({{ $journal->student->nis }})</h4>
                            <p class="text-muted mb-0">Tanggal Jurnal: <span class="fw-semibold">{{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('l, d F Y') }}</span></p>
                        </div>
                        <div>
                             @if($journal->status == 'menunggu')
                                <span class="badge bg-soft-warning text-warning">Menunggu Validasi</span>
                            @elseif($journal->status == 'disetujui')
                                <span class="badge bg-soft-success text-success">Sudah Disetujui</span>
                            @else
                                <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kolom Kiri: Detail Checklist -->
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3 text-dark">Capaian Kebiasaan</h5>
                                    <ul class="list-group list-group-flush bg-transparent">
                                        @foreach($journal->details as $detail)
                                            <li class="list-group-item bg-transparent px-0 py-3 border-bottom">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="d-flex align-items-start">
                                                        @if($detail->habit && $detail->habit->icon)
                                                            <i class="{{ $detail->habit->icon }} me-3 mt-1 text-muted fs-5"></i>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-1 text-dark fw-semibold">{{ $detail->habit->name ?? 'Kebiasaan' }}</h6>
                                                            
                                                            {{-- Show Reason or Time --}}
                                                            @if($detail->nilai == 0)
                                                                @if($detail->note)
                                                                    <div class="alert alert-soft-danger p-2 mt-2 mb-0 fs-12">
                                                                        <strong>Alasan:</strong> {{ $detail->note }}
                                                                    </div>
                                                                @endif
                                                            @elseif($detail->nilai == 1 && $detail->actual_value)
                                                                <div class="text-muted fs-12 mt-1">
                                                                    <i class="feather-clock me-1"></i> Pukul {{ $detail->actual_value }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    @if($detail->nilai)
                                                        <span class="badge bg-soft-success text-success">
                                                            <i class="feather-check me-1"></i> Dilaksanakan
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-danger text-danger">
                                                            <i class="feather-x me-1"></i> Tidak
                                                        </span>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Form Validasi Guru -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3 text-primary">Validasi Wali Kelas</h5>
                                    
                                    <form action="{{ route('teacher.journals.update', $journal->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Keputusan Status:</label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input type="radio" name="status" id="status_disetujui" value="disetujui" class="form-check-input" {{ $journal->status == 'disetujui' ? 'checked' : '' }} required>
                                                    <label class="form-check-label text-success fw-semibold" for="status_disetujui">Setujui</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="status" id="status_pembinaan" value="pembinaan" class="form-check-input" {{ $journal->status == 'pembinaan' ? 'checked' : '' }}>
                                                    <label class="form-check-label text-danger fw-semibold" for="status_pembinaan">Perlu Pembinaan</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="status" id="status_menunggu" value="menunggu" class="form-check-input" {{ $journal->status == 'menunggu' ? 'checked' : '' }}>
                                                    <label class="form-check-label text-warning fw-semibold" for="status_menunggu">Menunggu (Reset)</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label for="catatan_guru" class="form-label fw-bold">Catatan / Komentar:</label>
                                            <textarea name="catatan_guru" id="catatan_guru" rows="4" class="form-control" placeholder="Berikan semangat atau catatan perbaikan...">{{ $journal->catatan_guru }}</textarea>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('teacher.journals.index', ['date' => $journal->tanggal]) }}" class="btn btn-light">
                                                Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Simpan Validasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
