<x-backend-layout>
    <x-slot name="header">
        Edit User
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap:</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-control" placeholder="Masukkan Email" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password (Kosongkan jika tidak diubah):</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password Baru">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="role" class="form-label">Role:</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Sekolah</option>
                                    <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    <option value="orang_tua" {{ $user->role == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="school_id" class="form-label">Sekolah:</label>
                                <select name="school_id" id="school_id" class="form-control">
                                    <option value="">-- Pilih Sekolah --</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $user->school_id == $school->id ? 'selected' : '' }}>{{ $school->nama_sekolah }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
