<x-backend-layout>
    <x-slot name="header">
        Kenaikan Kelas (Promosi Massal)
    </x-slot>

    @php
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\ClassRoom[] $classes */
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Student[] $students */
        /** @var int|string|null $sourceClassId */
    @endphp

    <div class="row">
        <div class="col-lg-12">
            
            {{-- Step 1: Filter Source Class --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">1. Pilih Kelas Asal</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('students.promote') }}" method="GET" class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kelas Asal</label>
                            <select name="source_class_id" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $sourceClassId == $class->id ? 'selected' : '' }}>
                                        {{ $class->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                             <button type="submit" class="btn btn-primary w-100">
                                <i class="feather-filter me-2"></i> Tampilkan
                             </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($sourceClassId && count($students) > 0)
            {{-- Step 2: Select Students & Target --}}
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">2. Pilih Siswa & Kelas Tujuan</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('students.promote_store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="source_class_id" value="{{ $sourceClassId }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                     <label class="form-label fw-bold">Pindahkan Siswa Terpilih Ke:</label>
                                     <select name="target_class_id" class="form-select" required>
                                         <option value="" disabled selected>-- Pilih Kelas Tujuan --</option>
                                         <option value="GRADUATED" class="fw-bold text-danger">🎓 LULUS / TAMAT SEKOLAH</option>
                                         <option disabled>----------------</option>
                                         @foreach($classes as $class)
                                            {{-- Hide source class from target options --}}
                                            @if($class->id != $sourceClassId)
                                                <option value="{{ $class->id }}">{{ $class->nama_kelas }}</option>
                                            @endif
                                         @endforeach
                                     </select>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Yakin ingin memindahkan siswa yang dipilih?')">
                                    <i class="feather-check-circle me-2"></i> Proses Kenaikan Kelas
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
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
                                        <th>Gender</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input student-checkbox" type="checkbox" name="ids[]" value="{{ $student->id }}">
                                            </div>
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-bold">{{ $student->nama }}</td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->gender == 'L' ? 'L' : 'P' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
            @elseif($sourceClassId)
                <div class="alert alert-warning text-center">
                    <i class="feather-info fs-1 d-block mb-2"></i>
                    Belum ada siswa di kelas ini.
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.student-checkbox');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
    @endpush
</x-backend-layout>
