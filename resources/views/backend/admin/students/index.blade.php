<x-backend-layout>
    <x-slot name="header">
        Daftar Siswa
    </x-slot>

    <div class="row mb-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-soft-success text-success mb-0 border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bolder mb-1">{{ $totalSiswa }}</h2>
                        <span class="fs-12 fw-bold text-uppercase letter-spacing-1">Total Siswa</span>
                    </div>
                    <div class="avatar-text avatar-lg bg-white text-success rounded-circle">
                        <i class="feather-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-soft-primary text-primary mb-0 border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bolder mb-1">{{ $totalLaki }}</h2>
                        <span class="fs-12 fw-bold text-uppercase letter-spacing-1">Siswa Laki-Laki</span>
                    </div>
                    <div class="avatar-text avatar-lg bg-white text-primary rounded-circle">
                        <i class="feather-user"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="card bg-soft-danger text-danger mb-0 border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bolder mb-1">{{ $totalPerempuan }}</h2>
                        <span class="fs-12 fw-bold text-uppercase letter-spacing-1">Siswa Perempuan</span>
                    </div>
                    <div class="avatar-text avatar-lg bg-white text-danger rounded-circle">
                        <i class="feather-user"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="card-title mb-0">Data Siswa</h5>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Search Form -->
                        <form action="{{ route('students.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari Nama/NIS/Kelas..." value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit"><i class="feather-search"></i></button>
                            </div>
                        </form>

                        <div class="card-header-action d-flex flex-wrap gap-2">
                            <!-- Bulk Delete Button (Hidden by default) -->
                            <button type="submit" form="bulk-delete-form" id="btn-bulk-delete" class="btn btn-danger d-none">
                                <i class="feather-trash-2"></i> <span class="d-none d-md-inline ms-1">Hapus</span>
                            </button>
                            
                            <a href="{{ route('students.promote') }}" class="btn btn-info" data-bs-toggle="tooltip" title="Kenaikan Kelas">
                                 <i class="feather-trending-up"></i> <span class="d-none d-md-inline ms-1">Naik Kelas</span>
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal" title="Import Excel">
                                <i class="feather-upload"></i> <span class="d-none d-md-inline ms-1">Import</span>
                            </button>
                            <a href="{{ route('students.export') }}" class="btn btn-warning" title="Export Data">
                                 <i class="feather-download"></i> <span class="d-none d-md-inline ms-1">Export</span>
                            </a>
                            <a href="{{ route('students.create') }}" class="btn btn-primary" title="Tambah Siswa">
                                <i class="feather-plus"></i> <span class="d-none d-md-inline ms-1">Tambah</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0" id="students-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="select-all">
                                            </div>
                                        </th>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Email</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input student-checkbox" type="checkbox" name="ids[]" value="{{ $student->id }}">
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration + $students->firstItem() - 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-image">
                                                    @if($student->user->avatar)
                                                        <img src="{{ asset('storage/' . $student->user->avatar) }}" alt="" class="img-fluid" />
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama) }}&background=random" alt="" class="img-fluid" />
                                                    @endif
                                                </div>
                                                <a href="javascript:void(0)" class="fw-bold text-dark">{{ $student->nama }}</a>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $student->nis }}</span></td>
                                        <td><span class="badge bg-soft-info text-info">{{ $student->classRoom->nama_kelas ?? '-' }}</span></td>
                                        <td>{{ $student->user->email }}</td>
                                        <td class="text-end">
                                            <div class="hstack gap-2 justify-content-end">
                                                <a href="{{ route('students.print_report', $student->id) }}" class="btn btn-soft-info btn-sm" target="_blank" data-bs-toggle="tooltip" title="Cetak Rapor">
                                                    <i class="feather-printer"></i>
                                                </a>
                                                <button type="button" class="btn btn-soft-secondary btn-sm" onclick="confirmReset('{{ route('students.reset_password', $student->id) }}', '{{ $student->nama }}')" data-bs-toggle="tooltip" title="Reset Password">
                                                    <i class="feather-lock"></i>
                                                </button>
                                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-soft-warning btn-sm" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                {{-- Individual delete form --}}
                                                {{-- Since we have bulk delete form wrapping the table, we cannot nest forms. 
                                                   We must put individual delete buttons outside or use JS to submit a separate hidden form. --}}
                                                <button type="button" class="btn btn-soft-danger btn-sm" onclick="confirmDelete('{{ route('students.destroy', $student->id) }}')" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="feather-users fs-1 text-muted"></i>
                                            <p class="text-muted mt-2">Belum ada data siswa.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form for individual delete --}}
    <form id="delete-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    {{-- Hidden form for password reset --}}
    <form id="reset-form" method="POST" class="d-none">
        @csrf
    </form>

    {{-- Hidden form for bulk delete --}}
    <form id="bulk-delete-form" action="{{ route('students.bulk_destroy') }}" method="POST" class="d-none">
        @csrf
        <div id="bulk-ids-container"></div>
    </form>

    @push('modals')
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">File Excel (.xlsx)</label>
                                <input type="file" class="form-control" name="file" required accept=".xlsx, .xls, .csv">
                                <div class="form-text mt-2">
                                    Pastikan format sesuai template. <a href="{{ route('students.template') }}" class="fw-bold">Download Template</a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts') <!-- Assuming 'scripts' stack exists, typically defined in layout -->
    <script>
        // Select All Checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkDeleteButton();
        });

        // Individual Checkbox Change
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleBulkDeleteButton();
                // Uncheck "Select All" if any checkbox is unchecked
                if (!this.checked) {
                    document.getElementById('select-all').checked = false;
                }
            });
        });

        function toggleBulkDeleteButton() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const btn = document.getElementById('btn-bulk-delete');
            if (checkboxes.length > 0) {
                btn.classList.remove('d-none');
            } else {
                btn.classList.add('d-none');
            }
        }

        // Bulk Delete Action
        const bulkBtn = document.getElementById('btn-bulk-delete');
        if(bulkBtn) {
            bulkBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus data siswa yang dipilih? Data yang dihapus tidak dapat dikembalikan.')) {
                    const form = document.getElementById('bulk-delete-form');
                    const container = document.getElementById('bulk-ids-container');
                    container.innerHTML = ''; // Clear previous

                    document.querySelectorAll('.student-checkbox:checked').forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        container.appendChild(input);
                    });

                    form.submit();
                }
            });
        }

        // Individual Delete Confirmation
        window.confirmDelete = function(url) {
            if (confirm('Yakin ingin menghapus siswa ini?')) {
                const form = document.getElementById('delete-form');
                form.action = url;
                form.submit();
            }
        }

        // Reset Password Confirmation
        window.confirmReset = function(url, name) {
            if (confirm('Reset password siswa "' + name + '" menjadi "12345678"?')) {
                const form = document.getElementById('reset-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
    @endpush
</x-backend-layout>
