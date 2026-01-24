<x-backend-layout>
    <x-slot name="header">
        Daftar Sekolah
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Data Sekolah</h5>
                    <div class="card-header-action">
                        <a href="{{ route('schools.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i> Tambah Sekolah
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
                                    <th>Nama Sekolah</th>
                                    <th>Alamat</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schools as $school)
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" class="fw-bold">{{ $school->nama_sekolah }}</a>
                                    </td>
                                    <td>{{ $school->alamat }}</td>
                                    <td class="text-end">
                                        <div class="hstack gap-2 justify-content-end">
                                            <a href="{{ route('schools.edit', $school->id) }}" class="btn btn-soft-warning btn-sm">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');" class="d-inline">
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
                    {{ $schools->links() }}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
