<x-backend-layout>
    <x-slot name="header">
        Tambah User Baru
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="role" class="form-label">Role:</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin">Admin Sekolah</option>
                                    <option value="guru">Guru</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="orang_tua">Orang Tua</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="school_id" class="form-label">Sekolah (Wajib kecuali Super Admin):</label>
                                <select name="school_id" id="school_id" class="form-control">
                                    <option value="">-- Pilih Sekolah --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
