<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="theme_ocean">
    <title>{{ config('app.name', 'Laravel') }} || Reset Password</title>
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
                        <img src="{{ asset('template-admin/assets/images/auth/auth-cover-reset-bg.svg') }}" alt="" class="img-fluid">
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
                    <h2 class="fs-20 fw-bolder mb-4">Reset Password</h2>
                    <h4 class="fs-13 fw-bold mb-2">Lupa Password?</h4>
                    <p class="fs-12 fw-medium text-muted">Masukkan email Anda dan kami akan mengirimkan link untuk mereset password Anda.</p>
                    
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="w-100 mt-4 pt-2">
                        @csrf
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Kirim Link Reset</button>
                        </div>
                    </form>
                    <div class="mt-5 text-muted">
                        <span>Ingat password Anda?</span>
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
