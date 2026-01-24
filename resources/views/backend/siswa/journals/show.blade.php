<x-backend-layout>
    <x-slot name="header">
        Detail Jurnal
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    
                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">Jurnal Tanggal: {{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('l, d F Y') }}</h4>
                            <p class="text-muted mb-0">Status: 
                                @if($journal->status == 'menunggu')
                                    <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                @elseif($journal->status == 'disetujui')
                                    <span class="badge bg-soft-success text-success">Disetujui</span>
                                @else
                                    <span class="badge bg-soft-danger text-danger">Pembinaan</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="border-top pt-4">
                        <h5 class="fw-bold mb-3 text-dark">Checklist Kebiasaan:</h5>
                        <ul class="list-group list-group-flush">
                                    @foreach($journal->details as $detail)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            @if($detail->habit->icon)
                                                <i class="{{ $detail->habit->icon }} me-3 fs-4 text-primary"></i>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $detail->habit->name }}</h6>
                                                @if($detail->habit->input_type == 'time' && $detail->actual_value)
                                                    <small class="text-muted">Waktu: {{ $detail->actual_value }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($detail->nilai == 1)
                                                <span class="badge bg-soft-success text-success"><i class="feather-check me-1"></i>Terlaksana</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger"><i class="feather-x me-1"></i>Tidak</span>
                                            @endif
                                        </div>
                                    </li>
                                    @endforeach
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Catatan Saya:</h6>
                        <div class="alert alert-soft-secondary text-dark">
                            {{ $journal->catatan_siswa ?? 'Tidak ada catatan.' }}
                        </div>
                    </div>

                    @if($journal->catatan_guru)
                    <div class="alert alert-soft-warning mt-4 text-dark" role="alert">
                        <h5 class="alert-heading fw-bold mb-2 text-warning"><i class="feather-message-square me-2"></i>Catatan Guru:</h5>
                        <p class="mb-0">{{ $journal->catatan_guru }}</p>
                    </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('journals.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i> Kembali ke Riwayat
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
