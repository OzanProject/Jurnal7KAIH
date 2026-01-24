<x-backend-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between w-100">
            <span>Edit Master Kebiasaan</span>
            <a href="{{ route('habits.index') }}" class="btn btn-sm btn-secondary">
                <i class="feather-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card stretch stretch-full border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('habits.update', $habit->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nama Kebiasaan <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $habit->name }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nama Ikon (Feather Icon)</label>
                            <input type="text" name="icon" class="form-control" value="{{ $habit->icon }}">
                            <small class="text-muted">Gunakan class icon dari <a href="https://feathericons.com/" target="_blank">Feather Icons</a>.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ $habit->description }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Update Kebiasaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
