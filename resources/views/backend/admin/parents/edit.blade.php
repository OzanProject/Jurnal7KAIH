<x-backend-layout>
    <x-slot name="header">
        Edit Orang Tua
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Edit Data Orang Tua</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.parents.update', $parentUser->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Orang Tua <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $parentUser->name) }}" required>
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Untuk Login) <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $parentUser->email) }}" required>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="password" id="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_id" class="form-label">Pilih Siswa (Anak) <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="" disabled>Pilih siswa...</option>
                                @php
                                    $currentStudentId = $parentUser->parent ? $parentUser->parent->student_id : null;
                                @endphp
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $currentStudentId) == $student->id ? 'selected' : '' }}>
                                        {{ $student->nama }} ({{ $student->classRoom->nama_kelas }} - NIS: {{ $student->nis }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Pastikan memilih siswa yang benar.</div>
                            @error('student_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.parents.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
