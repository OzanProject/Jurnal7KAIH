<x-backend-layout>
    <x-slot name="header">
        Tambah Kelas Baru
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('classes.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="nama_kelas" class="form-label">Nama Kelas:</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" placeholder="Masukkan Nama Kelas" required>
                        </div>

                        <div class="mb-4">
                            <label for="wali_kelas_id" class="form-label">Wali Kelas:</label>
                            <select name="wali_kelas_id" id="wali_kelas_id" class="form-control">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                            <a href="{{ route('classes.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
