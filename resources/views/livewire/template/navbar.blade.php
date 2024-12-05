<div>
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex justify-content-center">
            <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
                <button
                    class="navbar-toggler navbar-toggler align-self-center"
                    type="button"
                    data-toggle="minimize">
                    <span class="typcn typcn-th-menu"></span>
                </button>
            </div>
        </div>

        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <ul class="navbar-nav me-lg-2">
                <li class="nav-item nav-profile dropdown">
                    <!-- <a
                        class="nav-link"
                        href="#"
                        data-bs-toggle="dropdown"
                        id="profileDropdown">
                        <img
                            src="../../../assets/images/faces/face5.jpg"
                            alt="profile" />
                        <span class="nav-profile-name">{{ Auth::user()->name }}</span>
                    </a> -->

                    <div class="profile-picture bg-color-{{ Auth::user()->id % 5 + 1 }} pointer" data-bs-toggle="dropdown">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <span class="nav-profile-name pointer" data-bs-toggle="dropdown">{{ Auth::user()->name }}</span>

                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="{{ route('change-password') }}">
                            <i class="typcn typcn-cog-outline text-primary"></i>
                            Account Settings
                        </a>

                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                            <i class="typcn typcn-eject text-primary"></i>
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                <!-- <li class="nav-item nav-user-status dropdown">
                    <p class="mb-0">Last login was 23 hours ago.</p>
                </li> -->
            </ul>

            <ul class="navbar-nav navbar-nav-right">
                <!-- <li class="nav-item nav-date dropdown">
                    <a
                        class="nav-link d-flex justify-content-center align-items-center"
                        href="javascript:;">
                        <h6 class="date mb-0">Today : Mar 23</h6>
                        <i class="typcn typcn-calendar"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a
                        class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                        id="messageDropdown"
                        href="#"
                        data-bs-toggle="dropdown">
                        <i class="typcn typcn-mail mx-0"></i>
                        <span class="count"></span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                        aria-labelledby="messageDropdown">
                        <p
                            class="mb-0 fw-normal float-start dropdown-header">
                            Messages
                        </p>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img
                                    src="../../../assets/images/faces/face4.jpg"
                                    alt="image"
                                    class="profile-pic" />
                            </div>
                            <div class="preview-item-content flex-grow">
                                <h6
                                    class="preview-subject ellipsis fw-normal">
                                    David Grey
                                </h6>
                                <p
                                    class="fw-light small-text text-muted mb-0">
                                    The meeting is cancelled
                                </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img
                                    src="../../../assets/images/faces/face2.jpg"
                                    alt="image"
                                    class="profile-pic" />
                            </div>
                            <div class="preview-item-content flex-grow">
                                <h6
                                    class="preview-subject ellipsis fw-normal">
                                    Tim Cook
                                </h6>
                                <p
                                    class="fw-light small-text text-muted mb-0">
                                    New product launch
                                </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <img
                                    src="../../../assets/images/faces/face3.jpg"
                                    alt="image"
                                    class="profile-pic" />
                            </div>
                            <div class="preview-item-content flex-grow">
                                <h6
                                    class="preview-subject ellipsis fw-normal">
                                    Johnson
                                </h6>
                                <p
                                    class="fw-light small-text text-muted mb-0">
                                    Upcoming board meeting
                                </p>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown me-0">
                    <a
                        class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
                        id="notificationDropdown"
                        href="#"
                        data-bs-toggle="dropdown">
                        <i class="typcn typcn-bell mx-0"></i>
                        <span class="count"></span>
                    </a>
                    <div
                        class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                        aria-labelledby="notificationDropdown">
                        <p
                            class="mb-0 fw-normal float-start dropdown-header">
                            Notifications
                        </p>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-success">
                                    <i
                                        class="typcn typcn-info mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject fw-normal">
                                    Application Error
                                </h6>
                                <p
                                    class="fw-light small-text mb-0 text-muted">
                                    Just now
                                </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-warning">
                                    <i
                                        class="typcn typcn-cog-outline mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject fw-normal">
                                    Settings
                                </h6>
                                <p
                                    class="fw-light small-text mb-0 text-muted">
                                    Private message
                                </p>
                            </div>
                        </a>
                        <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-info">
                                    <i
                                        class="typcn typcn-user mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject fw-normal">
                                    New user registration
                                </h6>
                                <p
                                    class="fw-light small-text mb-0 text-muted">
                                    2 days ago
                                </p>
                            </div>
                        </a>
                    </div>
                </li> -->
            </ul>

            <button
                class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                type="button"
                data-toggle="offcanvas">
                <span class="typcn typcn-th-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->

    <nav class="navbar-breadcrumb col-xl-12 col-12 d-flex flex-row p-0">
        <div class="navbar-links-wrapper d-flex align-items-stretch">
            <!-- <div class="nav-link">
                <a href="javascript:;"><i class="typcn typcn-calendar-outline"></i></a>
            </div>
            <div class="nav-link">
                <a href="javascript:;"><i class="typcn typcn-mail"></i></a>
            </div>
            <div class="nav-link">
                <a href="javascript:;"><i class="typcn typcn-folder"></i></a>
            </div>
            <div class="nav-link">
                <a href="javascript:;"><i class="typcn typcn-document-text"></i></a>
            </div> -->
        </div>
        <div
            class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <ul class="navbar-nav me-lg-2">
                <li class="nav-item ms-0">
                    <h4 class="mb-0">DEPOT MANAGEMENT SYSTEM</h4>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <!-- ... -->
            </ul>
        </div>
    </nav>
</div>