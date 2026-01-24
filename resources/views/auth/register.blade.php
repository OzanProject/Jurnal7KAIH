@php
    /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\ClassRoom> $classes */
@endphp
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="theme_ocean">
    <title>{{ config('app.name', 'Laravel') }} || Register</title>
    @php
        $appFavicon = \App\Models\Setting::get('app_favicon');
    @endphp
    @if($appFavicon)
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset($appFavicon) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template-admin/assets/images/favicon.ico') }}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/css/theme.min.css') }}">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <main class="auth-cover-wrapper">
        <div class="auth-cover-content-inner">
            <div class="auth-cover-content-wrapper">
                <div class="auth-img">
                    @php
                        $loginBg = \App\Models\Setting::get('login_bg_image');
                    @endphp
                    @if($loginBg)
                        <img src="{{ asset($loginBg) }}" alt="" class="img-fluid">
                    @else
                        <img src="{{ asset('template-admin/assets/images/auth/auth-cover-register-bg.svg') }}" alt="" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-50 mb-5">
                       @php
                           $appLogo = \App\Models\Setting::get('app_logo');
                       @endphp
                       @if($appLogo)
                            <img src="{{ asset($appLogo) }}" alt="Logo" class="img-fluid" style="max-height: 50px;">
                       @else
                            <img src="{{ asset('template-admin/assets/images/logo-abbr.png') }}" alt="" class="img-fluid">
                       @endif
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">Register</h2>
                    <h4 class="fs-13 fw-bold mb-2">Buat Akun Baru</h4>
                    <p class="fs-12 fw-medium text-muted">Silakan lengkapi data berikut untuk mendaftar.</p>
                    
                    <form method="POST" action="{{ route('register') }}" class="w-100 mt-4 pt-2" x-data="{ role: 'siswa' }">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus autocomplete="name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                             <label class="form-label fs-12 text-muted">Daftar Sebagai</label>
                             <select name="role" class="form-control" x-model="role">
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="orang_tua">Orang Tua</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2 text-danger" />
                        </div>
                        
                        <!-- Class (Siswa Only) -->
                        <div class="mb-4" x-show="role === 'siswa'" style="display: none;">
                             <label class="form-label fs-12 text-muted">Kelas</label>
                             <select name="class_id" class="form-control">
                                <option value="">Pilih Kelas</option>
                                @if(isset($classes) && is_iterable($classes))
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->nama_kelas }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-2 text-danger" />
                        </div>

                        <!-- Gender (Siswa Only) -->
                        <div class="mb-4" x-show="role === 'siswa'" style="display: none;">
                             <label class="form-label fs-12 text-muted">Jenis Kelamin</label>
                             <select name="gender" class="form-control">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2 text-danger" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                        </div>
                        
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Daftar Sekarang</button>
                        </div>
                    </form>
                    <div class="mt-5 text-muted">
                        <span>Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="fw-bold">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('template-admin/assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/js/common-init.min.js') }}"></script>
</body>

</html>
