<div>
    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="menu-title">Dashboard</span>
                    <div class="badge badge-danger">new</div>
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-bs-toggle="collapse"
                    href="#ui-basic"
                    aria-expanded="false"
                    aria-controls="ui-basic">
                    <i class='bx bx-down-arrow-alt bx-sm'></i>
                    <span class="menu-title">Incoming</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="pages/ui-features/buttons.html">Documents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/ui-features/dropdowns.html">Requests</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class='bx bx-up-arrow-alt bx-sm'></i>
                    <span class="menu-title">Outgoing</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class='bx bxs-file bx-sm'></i>
                    <span class="menu-title">WEW</span>
                </a>
            </li>

            <li class="nav-item">
                <a
                    class="nav-link"
                    data-bs-toggle="collapse"
                    href="#auth"
                    aria-expanded="false"
                    aria-controls="auth">
                    <i class='bx bxs-cog bx-sm'></i>
                    <span class="menu-title">Settings</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="auth">
                    @can('can read mechanics')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('mechanics') }}">
                                Mechanics
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('can read category')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="#">
                                Category
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('can read location')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="#">
                                Location
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('can read user management')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('user-management') }}">
                                User Management
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('can read roles')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('roles') }}">
                                Roles
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('can read permissions')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('permissions') }}">
                                Permissions
                            </a>
                        </li>
                    </ul>
                    @endcan
                </div>
            </li>
        </ul>
    </nav>
</div>