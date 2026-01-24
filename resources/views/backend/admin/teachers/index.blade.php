<x-backend-layout>
    <x-slot name="header">
        Daftar Guru
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Data Guru</h5>
                    <div class="card-header-action">
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Tambah Guru
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
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teachers as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-image">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($teacher->name) }}&background=random" alt="" class="img-fluid" />
                                            </div>
                                            <a href="javascript:void(0)" class="fw-bold">{{ $teacher->name }}</a>
                                        </div>
                                    </td>
                                    <td>{{ $teacher->email }}</td>
                                    <td class="text-end">
                                        <div class="hstack gap-2 justify-content-end">
                                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-soft-warning btn-sm">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" class="d-inline">
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
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
