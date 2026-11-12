    <div class="main-wrapper">

        <!-- START: Top Navbar Component -->
        <header class="navbar-custom">
            <div class="navbar-left">
                <!-- Desktop sidebar toggle (visible on large screens only) -->
                <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
                    id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
                    <i class="bi bi-chevron-bar-left"></i>
                </button>
                <!-- Mobile sidebar toggle -->
                <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Quick Actions Dropdown (admin only) -->
                @if ((Auth::user()->role ?? '') == 'admin')
                <div class="dropdown ms-2">
                    <button class="btn-quick-action dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" id="quick-actions-dropdown">
                        <i class="bi bi-plus-lg"></i>
                        <span>Create</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-quick-action" aria-labelledby="quick-actions-dropdown">
                        <li class="dropdown-header">Quick Action Shortcuts</li>
                        <li><a class="dropdown-item" href="{{ route('student.student_create') }}"><i class="bi bi-person-plus"></i> New Student</a></li>
                        <li><a class="dropdown-item" href="{{ route('teacher.teacher_create') }}"><i class="bi bi-person-badge"></i> New Lecturer</a></li>
                        <li><a class="dropdown-item" href="{{ route('subject.module_create') }}"><i class="bi bi-journal-plus"></i> New Module</a></li>
                    </ul>
                </div>
                @endif
            </div>

            <!-- Mid navbar: search pill -->
            <div class="navbar-search-wrapper">
                <input type="text" class="navbar-search-input" placeholder="Search..." id="main-search">
                <button class="navbar-search-btn" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>
            </div>

            <!-- Right actions -->
            <div class="navbar-actions">
                <!-- Fullscreen Toggle -->
                <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
                <div class="dropdown">
                    <button class="navbar-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" id="btn-notifications" data-bs-auto-close="outside">
                        <i class="bi bi-bell"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-notification p-0"
                        aria-labelledby="btn-notifications">
                        <div class="notification-header">
                            <h6 class="notification-title">Notifications</h6>
                        </div>
                        <div class="notification-list">
                            <p class="text-muted text-center py-4 mb-0">No new notifications</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown ms-2">
                    <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" id="profile-dropdown">
                        <img src="{{asset('assets/images/avatar.png')}}" alt="Profile Image" class="navbar-profile-img">
                        <span class="navbar-profile-name d-none d-md-inline">{{ Auth::user()->name ?? 'Guest' }}</span>
                        <i class="bi bi-chevron-down navbar-profile-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
                        <li class="dropdown-header">Welcome, {{ Auth::user()->name ?? 'Guest' }}</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <!-- END: Top Navbar Component -->