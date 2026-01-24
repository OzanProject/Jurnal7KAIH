<x-backend-layout>
    <x-slot name="header">
        Manajemen Tahun Ajaran
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Tahun Ajaran</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createYearModal">
                        <i class="feather-plus me-1"></i> Tambah Tahun Ajaran
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                             {{ session('success') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                     @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                             {{ session('error') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($academicYears as $year)
                                    <tr class="{{ $year->is_active ? 'table-success' : '' }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $year->name }}</td>
                                        <td>{{ $year->semester }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($year->start_date)->translatedFormat('d M Y') }} s/d 
                                            {{ \Carbon\Carbon::parse($year->end_date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td>
                                            @if($year->is_active)
                                                <span class="badge bg-success">AKTIF</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" 
                                                class="btn btn-sm btn-warning me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editYearModal"
                                                data-id="{{ $year->id }}"
                                                data-name="{{ $year->name }}"
                                                data-semester="{{ $year->semester }}"
                                                data-start="{{ $year->start_date ? \Carbon\Carbon::parse($year->start_date)->format('Y-m-d') : '' }}"
                                                data-end="{{ $year->end_date ? \Carbon\Carbon::parse($year->end_date)->format('Y-m-d') : '' }}"
                                                data-active="{{ $year->is_active }}"
                                                title="Edit">
                                                <i class="feather-edit"></i>
                                            </button>
                                            @if(!$year->is_active)
                                                <form action="{{ route('academic-years.activate', $year->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Aktifkan">
                                                        <i class="feather-check-circle"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('academic-years.destroy', $year->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tahun ajaran ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted small fst-italic ms-1">Sedang Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data tahun ajaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
    <div class="modal fade" id="createYearModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Ajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('academic-years.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Tahun Ajaran</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: 2025/2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mulai</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selesai</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck">
                            <label class="form-check-label" for="isActiveCheck">
                                Set sebagai Aktif?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editYearModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editYearForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Tahun Ajaran</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" id="edit_semester" class="form-select" required>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mulai</label>
                                <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selesai</label>
                                <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active">
                            <label class="form-check-label" for="edit_is_active">
                                Set sebagai Aktif?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editYearModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var semester = button.getAttribute('data-semester');
                var start = button.getAttribute('data-start');
                var end = button.getAttribute('data-end');
                var active = button.getAttribute('data-active');

                var form = document.getElementById('editYearForm');
                // Use generic URL, assuming route prefix 'super-admin'
                form.action = "{{ url('super-admin/academic-years') }}/" + id;

                document.getElementById('edit_name').value = name;
                document.getElementById('edit_semester').value = semester;
                document.getElementById('edit_start_date').value = start;
                document.getElementById('edit_end_date').value = end;
                document.getElementById('edit_is_active').checked = (active == "1");
            });
        });
    </script>
    @endpush
</x-backend-layout>
