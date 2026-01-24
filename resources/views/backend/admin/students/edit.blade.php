@php
    /** @var \App\Models\Student $student */
    /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\ClassRoom> $classes */
@endphp
<x-backend-layout>
    <x-slot name="header">
        Edit Data Siswa
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Data Akun & Akademik -->
                        <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted border-bottom pb-2">Data Akun & Akademik</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" name="email" id="email" value="{{ $student->user->email }}" class="form-control" placeholder="Masukkan Email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password (Biarkan kosong jika tetap):</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password Baru">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nis" class="form-label">NIS:</label>
                                <input type="text" name="nis" id="nis" value="{{ $student->nis }}" class="form-control" placeholder="Nomor Induk Siswa" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nisn" class="form-label">NISN (Opsional):</label>
                                <input type="text" name="nisn" id="nisn" value="{{ $student->nisn }}" class="form-control" placeholder="Nomor Induk Siswa Nasional">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="class_id" class="form-label">Kelas:</label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>{{ $class->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Data Pribadi -->
                        <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted border-bottom pb-2">Data Pribadi</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap:</label>
                                <input type="text" name="nama" id="nama" value="{{ $student->nama }}" class="form-control" placeholder="Nama Lengkap Siswa" required>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin:</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="L" {{ $student->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ $student->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir:</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ $student->tempat_lahir }}" class="form-control" placeholder="Kota Kelahiran">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir:</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $student->tanggal_lahir }}" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap:</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat tempat tinggal">{{ $student->alamat }}</textarea>
                            </div>
                        </div>

                        <!-- Data Orang Tua -->
                        <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted border-bottom pb-2">Data Orang Tua</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah:</label>
                                <input type="text" name="nama_ayah" id="nama_ayah" value="{{ $student->nama_ayah }}" class="form-control" placeholder="Nama Lengkap Ayah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah:</label>
                                <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" value="{{ $student->pekerjaan_ayah }}" class="form-control" placeholder="Pekerjaan Ayah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu:</label>
                                <input type="text" name="nama_ibu" id="nama_ibu" value="{{ $student->nama_ibu }}" class="form-control" placeholder="Nama Lengkap Ibu">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu:</label>
                                <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" value="{{ $student->pekerjaan_ibu }}" class="form-control" placeholder="Pekerjaan Ibu">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_hp_ortu" class="form-label">No. HP Orang Tua (WA):</label>
                                <input type="text" name="no_hp_ortu" id="no_hp_ortu" value="{{ $student->no_hp_ortu }}" class="form-control" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
