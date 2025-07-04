<div>
    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="menu-title">Dashboard</span>
                    <!-- <div class="badge badge-danger">new</div> -->
                </a>
            </li>
            <!-- <li class="nav-item">
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
            </li> -->
            @can('read incoming')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('incoming') }}">
                    <i class='bx bx-down-arrow-alt bx-sm'></i>
                    <span class="menu-title">Incoming</span>
                </a>
            </li>
            @endcan

            <!-- <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class='bx bx-up-arrow-alt bx-sm'></i>
                    <span class="menu-title">Outgoing</span>
                </a>
            </li> -->

            @can('read reports')
            <li class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                <a
                    class="nav-link"
                    data-bs-toggle="collapse"
                    href="#auth"
                    aria-expanded="false"
                    aria-controls="auth">
                    <i class='bx bxs-file bx-sm'></i>
                    <span class="menu-title">Reports</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="auth">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('weekly-depot-repair-bay-vehicle-or-equipment-inventory') }}"
                                title="Weekly Depot Repair Bay Vehicle / Equipment Inventory Report">
                                Weekly Depot Repair Bay Vehicle / Equipment Inventory Report
                            </a>
                        </li>
                    </ul>

                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('mechanics-job-order') }}"
                                title="Mechanics Job Order Report">
                                Mechanics Job Order Report
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endcan

            @can('read mechanic list')
            <li class="nav-item {{ request()->is('mechanics-list*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('mechanics-list') }}">
                    <i class='bx bxs-wrench bx-sm'></i>
                    <span class="menu-title">Mechanic (List)</span>
                </a>
            </li>
            @endcan

            <li class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                <a
                    class="nav-link"
                    data-bs-toggle="collapse"
                    href="#settings"
                    aria-expanded="false"
                    aria-controls="settings">
                    <i class='bx bxs-cog bx-sm'></i>
                    <span class="menu-title">Settings</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="settings">
                    @can('read types')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('equipment-or-vehicle-type') }}"
                                title="Type (Equipment / Vehicle)">
                                Type (Equipment / Vehicle)
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('read models')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('equipment-or-vehicle-model') }}"
                                title="Model (Equipment / Vehicle)">
                                Model (Equipment / Vehicle)
                            </a>
                        </li>
                    </ul>
                    @endcan
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
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('sections-mechanic') }}"
                                title="Sections (Mechanics)">
                                Sections (Mechanics)
                            </a>
                        </li>
                    </ul>
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('sub-sections-mechanic') }}"
                                title="Sub-sections (Mechanics)">
                                Sub-sections (Mechanics)
                            </a>
                        </li>
                    </ul>
                    @can('can read category')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('category') }}">
                                Category
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('read sub-category')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('sub-category') }}">
                                Sub-category
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('can read location')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('location') }}">
                                Location
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('read offices')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('office') }}">
                                Office
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('read status')
                    <!-- <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('status') }}">
                                Status
                            </a>
                        </li>
                    </ul> -->
                    @endcan
                    @can('read type of repair')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('type-of-repair') }}">
                                Type of Repair
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('read signatory')
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="{{ route('signatories') }}">
                                Signatories
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