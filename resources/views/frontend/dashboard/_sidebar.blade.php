<!-- Sidebar -->
 <aside class="col-lg-3 col-md-4 nav-sticky dashboard-sidebar">
            <!-- User Info Section -->
            <div class="user-info text-center p-3">
                <img src="{{ asset('frontend/img/user/' . auth()->user()->image) }}" alt="User Image"
                    class="rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover" />
                <h5 class="mb-0" style="color: #ff6f61">{{ auth()->user()->name }}</h5>
            </div>

            <!-- Sidebar Menu -->
            <div class="list-group profile-sidebar-menu">
                <a href="{{ route('frontend.dashboard.profile') }}"
                    class="list-group-item list-group-item-action menu-item{{ Route::currentRouteName() === 'frontend.dashboard.profile' ? ' active' : '' }}"
                    data-section="profile">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a href="{{ route('frontend.dashboard.notification') }}"
                    class="list-group-item list-group-item-action menu-item{{ Route::currentRouteName() === 'frontend.dashboard.notification' ? ' active' : '' }}"
                    data-section="notifications">
                    <i class="fas fa-bell"></i> Notifications
                </a>
                <a href="{{ route('frontend.dashboard.setting') }}"
                    class="list-group-item list-group-item-action menu-item{{ Route::currentRouteName() === 'frontend.dashboard.setting' ? ' active' : '' }}"
                    data-section="settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action menu-item text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
</aside>

