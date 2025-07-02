<div>
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        @hasanyrole('Super Administrator|Administrator|Regular User|Viewer')
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            @can('read incoming')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('incoming') }}">
                    <i class='bx bx-down-arrow-alt bx-sm'></i>
                    <span class="menu-title">Incoming</span>
                </a>
            </li>
            @endcan

            @can('read reports')
            <li class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
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
                    {{-- FIXED: All report links are now inside a single ul --}}
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('weekly-depot-repair-bay-vehicle-or-equipment-inventory') }}"
                                title="Weekly Depot Repair Bay Vehicle / Equipment Inventory Report">
                                Weekly Depot Repair Bay Vehicle / Equipment Inventory Report
                            </a>
                        </li>
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
                    <ul class="nav flex-column sub-menu" style="max-height: 300px; overflow-y: auto;">
                        @can('read types')
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('equipment-or-vehicle-type') }}"
                                title="Type (Equipment / Vehicle)">
                                Type (Equipment / Vehicle)
                            </a>
                        </li>
                        @endcan
                        @can('read models')
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('equipment-or-vehicle-model') }}"
                                title="Model (Equipment / Vehicle)">
                                Model (Equipment / Vehicle)
                            </a>
                        </li>
                        @endcan
                        @can('can read mechanics')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mechanics') }}">
                                Mechanics
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('sections-mechanic') }}"
                                title="Sections (Mechanics)">
                                Sections (Mechanics)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link text-truncate"
                                href="{{ route('sub-sections-mechanic') }}"
                                title="Sub-sections (Mechanics)">
                                Sub-sections (Mechanics)
                            </a>
                        </li>
                        @can('can read category')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('category') }}">
                                Category
                            </a>
                        </li>
                        @endcan
                        @can('read sub-category')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sub-category') }}">
                                Sub-category
                            </a>
                        </li>
                        @endcan
                        @can('can read location')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('location') }}">
                                Location
                            </a>
                        </li>
                        @endcan
                        @can('read offices')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('office') }}">
                                Office
                            </a>
                        </li>
                        @endcan
                        @can('read type of repair')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('type-of-repair') }}">
                                Type of Repair
                            </a>
                        </li>
                        @endcan
                        @can('read signatory')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('signatories') }}">
                                Signatories
                            </a>
                        </li>
                        @endcan
                        @can('can read user management')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user-management') }}">
                                User Management
                            </a>
                        </li>
                        @endcan
                        @can('can read roles')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('roles') }}">
                                Roles
                            </a>
                        </li>
                        @endcan
                        @can('can read permissions')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('permissions') }}">
                                Permissions
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
        </ul>
        @endhasanyrole
    </nav>
</div>