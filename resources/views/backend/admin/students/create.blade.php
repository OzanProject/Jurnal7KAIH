<x-backend-layout>
    <x-slot name="header">
        Tambah Siswa Baru
    </x-slot>

    @php
        /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\ClassRoom[] $classes */
    @endphp

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf
                        
                        <!-- Data Akun & Akademik -->
                        <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted border-bottom pb-2">Data Akun & Akademik</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" name="nis" id="nis" class="form-control" placeholder="Nomor Induk Siswa" value="{{ old('nis') }}" required>
                            </div>
                             <div class="col-md-4 mb-3">
                                <label for="nisn" class="form-label">NISN (Opsional)</label>
                                <input type="text" name="nisn" id="nisn" class="form-control" placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Data Pribadi -->
                        <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted border-bottom pb-2">Data Pribadi</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap Siswa" value="{{ old('nama') }}" required>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="" disabled selected>-- Pilih Gender --</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" placeholder="Kota Kelahiran" value="{{ old('tempat_lahir') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat tempat tinggal">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        <!-- Data Orang Tua -->
                        <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted border-bottom pb-2">Data Orang Tua</h5>
                        <div class="row mb-4">
                             <div class="col-md-6 mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah</label>
                                <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" placeholder="Nama Lengkap Ayah" value="{{ old('nama_ayah') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" placeholder="Pekerjaan Ayah" value="{{ old('pekerjaan_ayah') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu</label>
                                <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" placeholder="Nama Lengkap Ibu" value="{{ old('nama_ibu') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" placeholder="Pekerjaan Ibu" value="{{ old('pekerjaan_ibu') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_hp_ortu" class="form-label">No. HP Orang Tua (WA)</label>
                                <input type="text" name="no_hp_ortu" id="no_hp_ortu" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_hp_ortu') }}">
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-2"></i> Simpan Siswa
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
