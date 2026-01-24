<x-backend-layout>
    <x-slot name="header">
        Manajemen Orang Tua
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Daftar Orang Tua Siswa</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <form action="{{ route('admin.parents.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari Orang Tua / Siswa..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-light"><i class="feather-search"></i></button>
                        </form>
                        <a href="{{ route('admin.parents.create') }}" class="btn btn-primary text-nowrap">
                            <i class="feather-plus"></i> <span class="d-none d-md-inline ms-1">Tambah Orang Tua</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                             {{ session('success') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Email (Login)</th>
                                    <th>Siswa Terkait</th>
                                    <th>Kelas</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parents as $parent)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $parent->name }}</td>
                                        <td>{{ $parent->email }}</td>
                                        <td>
                                            @if($parent->parent && $parent->parent->student)
                                                <span class="fw-bold">{{ $parent->parent->student->nama }}</span>
                                                <div class="small text-muted">NIS: {{ $parent->parent->student->nis }}</div>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">Belum ditautkan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($parent->parent && $parent->parent->student && $parent->parent->student->classRoom)
                                                <span class="badge bg-soft-primary text-primary">{{ $parent->parent->student->classRoom->nama_kelas }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-soft-secondary btn-sm" onclick="confirmReset('{{ route('admin.parents.reset_password', $parent->id) }}', '{{ $parent->name }}')" data-bs-toggle="tooltip" title="Reset Password">
                                                    <i class="feather-lock"></i>
                                                </button>
                                                <a href="{{ route('admin.parents.edit', $parent->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.parents.destroy', $parent->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? User orang tua juga akan terhapus.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data orang tua.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $parents->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <form id="reset-form" method="POST" class="d-none">
        @csrf
    </form>
    
    @push('scripts')
    <script>
        window.confirmReset = function(url, name) {
            if (confirm('Reset password orang tua "' + name + '" menjadi "12345678"?')) {
                const form = document.getElementById('reset-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
    @endpush
</x-backend-layout>
