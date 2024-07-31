
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <ul class="sidebar-nav">
            <li class="sidebar-header"><h4><b class="text-center text-white size-1.5">Qr code service</b></h4></li>

            @can('user_access');
            <li class="sidebar-item {{ request()->is('/') ? 'active' : '' }}">
                <a class="sidebar-link" href="/">
                    <i class="align-middle" data-feather="home"></i>
                    <span class="align-middle">Dashboard</span>
                </a>
            </li>


            <li class="sidebar-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                    <i class="align-middle" data-feather="users"></i>
                    <span class="align-middle">Users</span>
                </a>
            </li>

            @can('role_access')
                <li class="sidebar-item {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.roles.index') }}">
                        <i class="align-middle" data-feather="user"></i>
                        <span class="align-middle">Roles</span>
                    </a>
                </li>
            @endcan

            @can('permission_access')
                <li class="sidebar-item {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('admin.permissions.index') }}">
                        <i class="align-middle" data-feather="sliders"></i>
                        <span class="align-middle">Permissions</span>
                    </a>
                </li>
            @endcan

            @endcan

            @can('qr_access')
            <li class="sidebar-item {{ request()->routeIs('admin.qrcodes.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.qrcodes.index') }}">
                    <i class="align-middle" data-feather="square"></i>
                    <span class="align-middle">Qr Codes</span>
                </a>
            </li>
            @endcan

        </ul>
    </div>
</nav>


