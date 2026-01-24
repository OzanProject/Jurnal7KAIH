<x-backend-layout>
    <x-slot name="header">
        Manajemen Users
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Daftar Users</h5>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i> Tambah User
                    </a>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Sekolah</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-image">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="" class="img-fluid" />
                                            </div>
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary">{{ ucfirst($user->role) }}</span>
                                    </td>
                                    <td>{{ $user->school->nama_sekolah ?? '-' }}</td>
                                    <td class="text-end">
                                        <div class="hstack gap-2 justify-content-end">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                     {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

</x-backend-layout>
