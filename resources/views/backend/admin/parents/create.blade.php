<x-backend-layout>
    <x-slot name="header">
        Tambah Orang Tua
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Form Data Orang Tua</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.parents.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Orang Tua <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Untuk Login) <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="text" name="password" id="password" class="form-control" value="12345678" required>
                            <div class="form-text">Default: 12345678. Bisa diganti.</div>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Pilih Siswa (Anak) <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="" disabled selected>Pilih siswa...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->nama }} ({{ $student->classRoom->nama_kelas }} - NIS: {{ $student->nis }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pastikan memilih siswa yang benar.</div>
                            @error('student_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
