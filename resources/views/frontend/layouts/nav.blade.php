

<!-- Brand Start -->
<div class="brand">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4">
                <div class="b-logo">
                    <a href="{{ route('frontend.home') }}" title="Home">
                        <img src="{{ asset('frontend/img/' . $setting->logo) }}" alt="Logo" />
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-4">
                <div class="b-ads">

                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="b-search">
                    <form action="{{ route('frontend.post.search') }}" method="get">
                        <input type="text" name="search" placeholder="Search for posts..." required />
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Brand End -->

<!-- Nav Bar Start -->
<div class="nav-bar">
    <div class="container">
        <nav class="navbar navbar-expand-md  navbar-dark">
            <a href="#" class="navbar-brand">MENU</a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav mr-auto">
                    <a href="{{ route('frontend.home') }}"
                        class="nav-item nav-link {{ Route::currentRouteName() === 'frontend.home' ? 'active' : '' }}">Home</a>
                    <div class="nav-item dropdown">
                        <a href="#"
                            class="nav-link dropdown-toggle {{ Route::currentRouteName() === 'frontend.category.posts' ? 'active' : '' }}"
                            data-toggle="dropdown">Categories</a>
                        <div class="dropdown-menu">
                            @foreach ($categories as $category)
                            <a href="{{ route('frontend.category.posts', $category->slug) }}"
                                title="{{ $category->name }}" class="dropdown-item">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('frontend.dashboard.profile') }}"
                        class="nav-item nav-link {{ Route::currentRouteName() === 'frontend.dashboard.profile' ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('frontend.contact.show') }}"
                        class="nav-item nav-link {{ Route::currentRouteName() === 'frontend.contact.show' ? 'active' : '' }}">Contact
                        Us</a>
                </div>

                <div class="navbar-nav ml-auto align-items-center">
                    <!-- Social icons -->
                    <div class="social d-none d-md-flex mr-3">
                        <a href="{{ $setting->twitter }}" title="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="{{ $setting->facebook }}" title="facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $setting->instagram }}" title="instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $setting->youtube }}" title="youtube"><i class="fab fa-youtube"></i></a>
                    </div>
                    @auth
                    <!-- Notification Dropdown (moved outside .social) -->

                    <div class="nav-item dropdown mr-3">
                        <a href="#" class="nav-link dropdown-toggle p-0 position-relative" id="notificationDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifications">
                            <i class="fas fa-bell"></i>
                            @if(Route::currentRouteName() !== 'frontend.dashboard.notification' &&
                            auth()->user()->unreadNotifications->count())
                            <span id="notification-count" class="badge badge-danger"
                                style="font-size:10px;position:absolute;top:0;right:0;">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown"
                            style="min-width:250px;">
                            <span class="dropdown-header">Notifications</span>
                            <div class="dropdown-divider"></div>
                            <div id='pusher-notification'>
                                @forelse (auth()->user()->unreadNotifications->take(5) as $notification)
                                <a class="dropdown-item"
                                    href="{{ $notification['data']['link'] }}?notify={{ $notification->id }}">{{
                                    $notification['data']['post_title'] }}
                                    <small class="text-muted ml-2" style="font-size:11px;">
                                        {{ \Carbon\Carbon::parse($notification['data']['commented_at'])->diffForHumans()
                                        }}
                                    </small>
                                </a>
                                @empty
                                <a class="dropdown-item text-center" href="#">No new notifications</a>
                                @endforelse
                            </div>
                            @if(auth()->user()->unreadNotifications->count())
                            <div class="dropdown-divider"></div>
                            <a id="view-all-notifications" class="dropdown-item text-center"
                                href="{{ route('frontend.dashboard.notification') }}">View all notifications</a>
                            <form method="POST" action="{{ route('frontend.dashboard.notification.delete_all') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-center text-danger"
                                    style="font-weight:bold;">
                                    Delete All
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>


                    <!-- User dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center flex-row-reverse"
                            id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <!-- صورة المستخدم -->
                            <img src="{{ asset('frontend/img/user/' . auth()->user()->image) }}" alt="User Image"
                                class="rounded-circle"
                                style="width:30px; height:30px; object-fit:cover; margin-left:8px;">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a href="" class="dropdown-item">Profile</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- إذا المستخدم مش مسجل دخول -->
                    <a href="{{ route('frontend.login') }}" class="nav-item nav-link">Login</a>
                    <a href="{{ route('frontend.register') }}" class="nav-item nav-link">Register</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Nav Bar End -->
