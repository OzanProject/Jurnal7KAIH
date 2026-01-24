<x-backend-layout>
    <x-slot name="header">
        Tambah Guru Baru
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('teachers.store') }}" method="POST">
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

                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                Simpan
                            </button>
                            <a href="{{ route('teachers.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
