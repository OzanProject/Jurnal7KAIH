<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand d-flex align-items-center gap-2">
                <!-- Single Door Logic: Settings Only -->
                @php
                    $appLogo = \App\Models\Setting::get('app_logo');
                    $appName = \App\Models\Setting::get('app_name', 'Jurnal 7 Kebiasaan');
                @endphp

                @if($appLogo)
                     <!-- Logo Image -->
                     <img src="{{ asset($appLogo) }}" alt="Logo" class="logo logo-lg" style="height: 40px; width: auto; object-fit: contain;">
                     <img src="{{ asset($appLogo) }}" alt="Logo" class="logo logo-sm" style="height: 30px; width: auto; object-fit: contain;">
                @else
                     <!-- Logo Fallback -->
                     <span class="logo logo-sm fs-2 fw-bold text-dark">J<span class="text-primary">7</span></span>
                @endif

                <!-- App Name Text (Visible only when expanded) -->
                <div class="logo logo-lg">
                    <span class="fw-bold text-dark d-block text-uppercase" style="font-size: 14px; line-height: 1.2; letter-spacing: 0.5px;">{{ $appName }}</span>
                </div>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Menu Utama</label>
                </li>



                <!-- ADMIN SEKOLAH MENUS -->
                @if(Auth::user()->role == 'admin')
                 <li class="nxl-item {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.admin') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-airplay"></i></span>
                         <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('classes.*') || request()->routeIs('teachers.*') || request()->routeIs('students.*') || request()->routeIs('admin.parents.*') || request()->routeIs('habits.*') || request()->routeIs('academic-years.*') || request()->routeIs('schools.*') || request()->routeIs('users.*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-database"></i></span>
                        <span class="nxl-mtext">Master Data</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('schools.index') ? 'active' : '' }}" href="{{ route('schools.index') }}">Data Sekolah</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">Data Pengguna</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}" href="{{ route('academic-years.index') }}">Tahun Ajaran</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('habits.*') ? 'active' : '' }}" href="{{ route('habits.index') }}">Master Kebiasaan</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}">Data Kelas</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">Data Guru</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Data Siswa</a></li>
                         <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.parents.*') ? 'active' : '' }}" href="{{ route('admin.parents.index') }}">Data Orang Tua</a></li>
                    </ul>
                </li>

                 <li class="nxl-item nxl-hasmenu {{ request()->routeIs('reports.*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-file-text"></i></span>
                         <span class="nxl-mtext">Laporan</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">Rekap Jurnal</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('reports.habitStats') ? 'active' : '' }}" href="{{ route('reports.habitStats') }}">Statistik Pembiasaan</a></li>
                    </ul>
                </li>
                
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.settings.*') || request()->routeIs('activity-logs.*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-settings"></i></span>
                         <span class="nxl-mtext">Pengaturan</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">Aplikasi</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}" href="{{ route('activity-logs.index') }}">Log Aktivitas</a></li>
                    </ul>
                </li>
                @endif

                 <!-- GURU MENUS -->
                @if(Auth::user()->role == 'guru')
                 <li class="nxl-item {{ request()->routeIs('dashboard.guru') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.guru') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-airplay"></i></span>
                         <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="nxl-item {{ request()->routeIs('teacher.journals.*') ? 'active' : '' }}">
                    <a href="{{ route('teacher.journals.index') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-check-circle"></i></span>
                         <span class="nxl-mtext">Validasi Jurnal</span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('teacher.reports.*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-file-text"></i></span>
                         <span class="nxl-mtext">Laporan</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('teacher.reports.index') ? 'active' : '' }}" href="{{ route('teacher.reports.index') }}">Rekap Jurnal</a></li>
                        <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('teacher.reports.habitStats') ? 'active' : '' }}" href="{{ route('teacher.reports.habitStats') }}">Statistik Pembiasaan</a></li>
                    </ul>
                </li>
                @endif

                <!-- SISWA MENUS -->
                @if(Auth::user()->role == 'siswa')
                 <li class="nxl-item {{ request()->routeIs('dashboard.siswa') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.siswa') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-airplay"></i></span>
                         <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                 <li class="nxl-item {{ request()->routeIs('journals.index') || request()->routeIs('journals.show') ? 'active' : '' }}">
                    <a href="{{ route('journals.index') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-book-open"></i></span>
                         <span class="nxl-mtext">Jurnal Saya</span>
                    </a>
                </li>
                 <li class="nxl-item {{ request()->routeIs('journals.create') ? 'active' : '' }}">
                    <a href="{{ route('journals.create') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-edit"></i></span>
                         <span class="nxl-mtext">Isi Jurnal</span>
                    </a>
                </li>
                @endif

                <!-- ORANG TUA MENUS -->
                @if(Auth::user()->role == 'orang_tua')
                 <li class="nxl-item {{ request()->routeIs('dashboard.orang_tua') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.orang_tua') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-airplay"></i></span>
                         <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                 <li class="nxl-item {{ request()->routeIs('parent.journals.*') ? 'active' : '' }}">
                    <a href="{{ route('parent.journals.index') }}" class="nxl-link">
                         <span class="nxl-micon"><i class="feather-book"></i></span>
                         <span class="nxl-mtext">Jurnal Anak</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
