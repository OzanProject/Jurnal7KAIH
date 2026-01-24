<x-backend-layout>
    <x-slot name="header">
        Riwayat Jurnal Anak
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Daftar Lengkap Jurnal: {{ $parent->student->nama }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Status Validasi</th>
                                    <th>Status Konfirmasi Ortu</th>
                                    <th>Catatan Guru</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($journals as $journal)
                                    <tr>
                                        <td>{{ $loop->iteration + $journals->firstItem() - 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($journal->tanggal)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            @if($journal->status == 'menunggu')
                                                <span class="badge bg-soft-warning text-warning">Menunggu</span>
                                            @elseif($journal->status == 'disetujui')
                                                <span class="badge bg-soft-success text-success">Disetujui</span>
                                            @elseif($journal->status == 'pembinaan')
                                                <span class="badge bg-soft-danger text-danger">Perlu Pembinaan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($journal->parent_confirmed_at)
                                                <span class="badge bg-soft-success text-success">
                                                    <i class="feather-check-circle me-1"></i> Dikonfirmasi
                                                </span>
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary">Belum</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ Str::limit($journal->catatan_guru, 40) ?? '-' }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('parent.journals.show', $journal->id) }}" class="btn btn-sm btn-info">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data jurnal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $journals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
