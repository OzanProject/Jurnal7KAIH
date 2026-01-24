<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="theme_ocean">
    <title>{{ config('app.name', 'Laravel') }} || Login</title>
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
                        <img src="{{ asset('template-admin/assets/images/auth/auth-cover-login-bg.svg') }}" alt="" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-50 mb-5">
                    <div class="wd-50 mb-5">
                       @php
                           $appLogo = \App\Models\Setting::get('app_logo');
                           $appName = \App\Models\Setting::get('app_name', config('app.name'));
                       @endphp
                       @if($appLogo)
                            <img src="{{ asset($appLogo) }}" alt="Logo" class="img-fluid" style="max-height: 50px;">
                       @else
                            <img src="{{ asset('template-admin/assets/images/logo-abbr.png') }}" alt="" class="img-fluid">
                       @endif
                    </div>
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">Login</h2>
                    <h4 class="fs-13 fw-bold mb-2">Login ke Akun Anda</h4>
                    <p class="fs-12 fw-medium text-muted">Selamat datang kembali di Aplikasi <strong>{{ config('app.name') }}</strong>.</p>
                    
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="w-100 mt-4 pt-2">
                        @csrf
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                    <label class="custom-control-label c-pointer" for="remember_me">Ingat Saya</label>
                                </div>
                            </div>
                            <div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="fs-11 text-primary">Lupa Password?</a>
                                @endif
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Login</button>
                        </div>
                    </form>
                    
                    <div class="mt-5 text-muted">
                        <span>Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="fw-bold">Buat Akun</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('template-admin/assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/js/common-init.min.js') }}"></script>
</body>

</html>
