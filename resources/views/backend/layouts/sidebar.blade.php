<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    @can('home')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.backend.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Users -->
    @can('users')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-user"></i>
            <span>User</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Management User :</h6>
                <a class="collapse-item" href="{{ route('admin.users.index') }}">List</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Nav Item - Categories -->
    @can('categories')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-list"></i>
            <span>Categories</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Category Managment:</h6>
                <a class="collapse-item" href="{{ route('admin.categories.index') }}">List</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Nav Item - Posts -->
    @can('posts')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePosts" aria-expanded="true"
            aria-controls="collapsePosts">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Posts</span>
        </a>
        <div id="collapsePosts" class="collapse" aria-labelledby="headingPosts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Post Management:</h6>
                <a class="collapse-item" href="{{ route('admin.posts.index') }}">List</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Nav Item - Settings -->
    @can('settings')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings"
            aria-expanded="true" aria-controls="collapseSettings">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
        </a>
        <div id="collapseSettings" class="collapse" aria-labelledby="headingSettings" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Settings:</h6>
                <a class="collapse-item" href="{{ route('admin.settings') }}">General</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Nav Item - Authorizations -->
    @can('authorizations')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAuthorizations"
            aria-expanded="true" aria-controls="collapseAuthorizations">
            <i class="fas fa-fw fa-key"></i>
            <span>Authorizations</span>
        </a>
        <div id="collapseAuthorizations" class="collapse" aria-labelledby="headingAuthorizations"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Authorization Management:</h6>
                <a class="collapse-item" href="{{ route('admin.authorizations.index') }}">Roles & Permissions</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Nav Item - Admins -->
    @can('admins')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true"
            aria-controls="collapseAdmin">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Admin</span>
        </a>
        <div id="collapseAdmin" class="collapse" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Admin Management:</h6>
                <a class="collapse-item" href="{{ route('admin.admin.index') }}">List</a>
            </div>
        </div>
    </li>
    @endcan

    <!-- Nav Item - Contacts -->
    @can('contact')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.contact') }}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Contacts</span>
        </a>
    </li>
    @endcan

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
