<x-backend-layout>
    <x-slot name="header">
        Daftar Kelas
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Data Kelas</h5>
                    <div class="card-header-action">
                        <a href="{{ route('classes.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Tambah Kelas
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

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classes as $class)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text bg-soft-primary text-primary rounded">
                                                {{ substr($class->nama_kelas, 0, 1) }}
                                            </div>
                                            <a href="javascript:void(0)" class="fw-bold">{{ $class->nama_kelas }}</a>
                                        </div>
                                    </td>
                                    <td>{{ $class->waliKelas ? $class->waliKelas->name : '-' }}</td>
                                    <td>{{ $class->students_count ?? 0 }}</td>
                                    <td class="text-end">
                                        <div class="hstack gap-2 justify-content-end">
                                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-soft-warning btn-sm">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger btn-sm">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $classes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
