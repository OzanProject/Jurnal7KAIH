<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ \App\Models\Setting::get('app_description', 'Aplikasi Jurnal Harian Siswa') }}" />
    <meta name="keyword" content="Jurnal, Siswa, Guru, Sekolah" />
    <meta name="author" content="{{ \App\Models\Setting::get('app_name', 'Jurnal 7 Kebiasaan') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--! The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags !-->
    
    <!--! BEGIN: Apps Title-->
    <title>{{ \App\Models\Setting::get('app_name', 'Jurnal 7 Kebiasaan') }} || {{ strip_tags($header ?? 'Dashboard') }}</title>
    <!--! END:  Apps Title-->
    
    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('app_favicon', 'template-admin/assets/images/favicon.ico')) }}" />
    <!--! END: Favicon-->
    
    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/css/bootstrap.min.css') }}" />
    <!--! END: Bootstrap CSS-->
    
    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/vendors/css/daterangepicker.min.css') }}" />
    <!--! END: Vendors CSS-->
    
    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('template-admin/assets/css/theme.min.css') }}" />
    <!--! END: Custom CSS-->
    
    <!--! HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries !-->
    <!--! WARNING: Respond.js doesn"t work if you view the page via file: !-->
    <!--[if lt IE 9]>
			<script src="https:oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https:oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>

<body>
    <!--! [Start] Navigation Manu !-->
    @include('layouts.partials.backend-sidebar')
    <!--! [End] Navigation Manu !-->

    <!--! [Start] Header !-->
    @include('layouts.partials.backend-header')
    <!--! [End] Header !-->

    <!--! [Start] Main Content !-->
    <!--! [Start] Main Content !-->
    <main class="nxl-container z-index-1 d-flex flex-column min-vh-100">
        <div class="nxl-content flex-grow-1">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ $header ?? 'Dashboard' }}</h5>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->

            <!-- [ Main Content ] start -->
             <div class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{ $slot }}
             </div>
            <!-- [ Main Content ] end -->
        </div>
        
        <!-- [ Footer ] start -->
        @include('layouts.partials.backend-footer')
        <!-- [ Footer ] end -->
    </main>
    <!--! [End] Main Content !-->

    <!--! [Start] Theme Customizer !-->
    @include('layouts.partials.backend-theme-settings')
    <!--! [End] Theme Customizer !-->

    <!--! BEGIN: Vendors JS !-->
    <script src="{{ asset('template-admin/assets/vendors/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="{{ asset('template-admin/assets/vendors/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/vendors/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/vendors/js/nxlNavigation.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
    <script src="{{ asset('template-admin/assets/vendors/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/vendors/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/vendors/js/circle-progress.min.js') }}"></script>
    <!--! END: Vendors JS !-->
    
    <!--! BEGIN: Apps Init  !-->
    <script src="{{ asset('template-admin/assets/js/common-init.min.js') }}"></script>
    <script src="{{ asset('template-admin/assets/js/theme-customizer-init.min.js') }}"></script>
    <!--! END: Apps Init !-->
    @stack('modals')
    @stack('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fix Sidebar Dropdown State on Reload
            const activeMenus = document.querySelectorAll('.nxl-item.nxl-hasmenu.active');
            activeMenus.forEach(menu => {
                menu.classList.add('nxl-trigger');
                const submenu = menu.querySelector('.nxl-submenu');
                if (submenu) {
                    submenu.style.display = 'block';
                }
            });
        });
    </script>
</body>

</html>
