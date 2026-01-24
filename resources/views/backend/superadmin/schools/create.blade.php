<x-backend-layout>
    <x-slot name="header">
        Tambah Sekolah Baru
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('schools.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3 text-primary">Informasi Dasar</h5>
                                <div class="mb-4">
                                    <label for="nama_sekolah" class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control" placeholder="Contoh: SMA Negeri 1 Jakarta" required>
                                </div>
                                <div class="mb-4">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Alamat lengkap sekolah"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label for="email" class="form-label">Email Sekolah</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="email@sekolah.sch.id">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="phone" class="form-label">No. Telepon</label>
                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="021-xxxxxxx">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" name="website" id="website" class="form-control" placeholder="https://sekolah.sch.id">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex align-items-center gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-1"></i> Simpan Data
                            </button>
                            <a href="{{ route('schools.index') }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
