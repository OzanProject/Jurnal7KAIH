<header class="nxl-header">
    <div class="header-wrapper">
        <!--! [Start] Header Left !-->
        <div class="header-left d-flex align-items-center gap-4">
            <!--! [Start] nxl-head-mobile-toggler !-->
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <!--! [End] nxl-head-mobile-toggler !-->

            <!--! [Start] nxl-navigation-toggle !-->
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
            <!--! [End] nxl-navigation-toggle !-->
        </div>
        <!--! [End] Header Left !-->
        
        <!--! [Start] Header Right !-->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center gap-1">
                
                <!--! [Start] Search (Restored Layout) !-->
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" class="nxl-head-link me-0" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <i class="feather-search"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown p-3">
                        <form action="{{ route('schools.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search Schools..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit"><i class="feather-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--! [End] Search !-->

                <!--! [Start] Notifications (Restored Layout) !-->
                <div class="dropdown nxl-h-item">
                     <a href="javascript:void(0);" class="nxl-head-link me-0" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <i class="feather-bell"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger nxl-h-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                        <div class="d-flex justify-content-between align-items-center dropdown-header">
                            <span class="fw-bold">Notifications</span>
                            <span class="badge bg-soft-danger text-danger">{{ Auth::user()->unreadNotifications->count() }} New</span>
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                             @forelse(Auth::user()->unreadNotifications as $notification)
                                <div class="alert alert-dismissible fade show mb-0" role="alert">
                                   <div class="d-flex align-items-center">
                                        <i class="feather-info me-2"></i>
                                        <span>{{ $notification->data['message'] ?? 'New Notification' }}</span>
                                   </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    <i class="feather-bell-off fs-1"></i>
                                    <p class="mb-0 mt-2">No new notifications</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!--! [End] Notifications !-->

                <!--! [Start] User Profile (Restored Layout) !-->
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                         @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="user-image" class="img-fluid user-avtar me-0" style="object-fit: cover; width: 40px; height: 40px;" />
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="user-image" class="img-fluid user-avtar me-0" />
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                 @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="user-image" class="img-fluid user-avtar" style="object-fit: cover; width: 40px; height: 40px;" />
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="user-image" class="img-fluid user-avtar" />
                                @endif
                                <div class="ms-2" style="min-width: 0;">
                                    <h6 class="text-dark mb-0 d-flex align-items-center">
                                        <span class="text-truncate" title="{{ Auth::user()->name }}" style="max-width: 150px;">{{ Auth::user()->name }}</span>
                                        <span class="badge bg-soft-primary text-primary ms-1 text-uppercase">{{ Auth::user()->role }}</span>
                                    </h6>
                                    <span class="fs-12 fw-medium text-muted text-truncate d-block" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="feather-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                             @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="dropdown-item">
                                <i class="feather-log-out"></i>
                                <span>Keluar</span>
                            </a>
                        </form>
                    </div>
                </div>
                <!--! [End] User Profile !-->

            </div>
        </div>
        <!--! [End] Header Right !-->
    </div>
</header>
