<x-backend-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between w-100">
            <span>Manajemen Master Kebiasaan</span>
            <a href="{{ route('habits.create') }}" class="btn btn-sm btn-primary">
                <i class="feather-plus me-2"></i>Tambah Kebiasaan
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Nama Kebiasaan</th>
                                    <th>Ikon</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($habits as $habit)
                                <tr>
                                    <td class="ps-4">{{ $loop->iteration }}</td>
                                    <td><span class="fw-bold text-dark">{{ $habit->name }}</span></td>
                                    <td>
                                        @if($habit->icon)
                                            <i class="{{ $habit->icon }} fs-4 text-primary"></i> <span class="ms-2 text-muted fs-11">({{ $habit->icon }})</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $habit->description }}</td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <a href="{{ route('habits.edit', $habit->id) }}" class="btn btn-sm btn-soft-warning">
                                                <i class="feather-edit-3"></i>
                                            </a>
                                            <form action="{{ route('habits.destroy', $habit->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kebiasaan ini? data jurnal yang terkait mungkin akan error tampilannya.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada data kebiasaan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
