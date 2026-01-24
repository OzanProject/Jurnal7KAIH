@php
    /** @var \App\Models\User $user */
    /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\User>|null $availableParents */
@endphp
<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-3">
             <label for="avatar" class="form-label">{{ __('Profile Picture') }}</label>
             <div class="d-flex align-items-center gap-3 mb-2">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="user-image" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                @endif
             </div>
             <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
             <x-input-error class="mt-2 text-danger" :messages="$errors->get('avatar')" />
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2 text-danger" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2 text-danger" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-muted small">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0 align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small fw-bold mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if($user->role === 'siswa' && $user->student)
            <hr class="my-4">
            <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted">Data Pribadi</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nisn" class="form-label">NISN (Opsional)</label>
                    <input type="text" name="nisn" id="nisn" class="form-control" value="{{ old('nisn', $user->student->nisn) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('gender', $user->student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $user->student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $user->student->tempat_lahir) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->student->tanggal_lahir) }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="2" class="form-control">{{ old('alamat', $user->student->alamat) }}</textarea>
                </div>
            </div>

            <h5 class="mb-3 text-uppercase fs-13 fw-bold text-muted mt-2">Data Orang Tua</h5>
            <div class="row">
                 <div class="col-md-6 mb-3">
                    <label for="nama_ayah" class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" value="{{ old('nama_ayah', $user->student->nama_ayah) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $user->student->pekerjaan_ayah) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_ibu" class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" value="{{ old('nama_ibu', $user->student->nama_ibu) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $user->student->pekerjaan_ibu) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="no_hp_ortu" class="form-label">No. HP Orang Tua (WA)</label>
                    <input type="text" name="no_hp_ortu" id="no_hp_ortu" class="form-control" value="{{ old('no_hp_ortu', $user->student->no_hp_ortu) }}">
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="parent_user_id" class="form-label">Tautkan Akun Orang Tua (Login)</label>
                    <select name="parent_user_id" id="parent_user_id" class="form-select">
                        <option value="">-- Pilih Akun Orang Tua (Opsional) --</option>
                        @php
                            $currentParentId = $user->student->parents->first()->user_id ?? null;
                        @endphp
                        @if(isset($availableParents))
                            @foreach($availableParents as $parentUser)
                                <option value="{{ $parentUser->id }}" {{ old('parent_user_id', $currentParentId) == $parentUser->id ? 'selected' : '' }}>
                                    {{ $parentUser->name }} ({{ $parentUser->email }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="form-text text-muted">Pilih akun orang tua yang akan memantau jurnal Anda.</div>
                </div>
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success py-1 px-3 mb-0" role="alert">
                    {{ __('Saved.') }}
                </div>
            @endif
        </div>
    </form>
</section>
