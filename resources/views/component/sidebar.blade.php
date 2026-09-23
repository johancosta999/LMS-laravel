<!-- ==========================================
             START: Sidebar Component
             Highly polished, dark-green sticky navigation
             ========================================== -->
<div class="sidebar-wrapper" id="sidebar">
  <!-- Brand Logo / Identity -->
  <a href="index.html" class="sidebar-brand">
    <i class="bi bi-asterisk"></i>
    <span>LMS</span>
  </a>

  <!-- Navigation Menu -->
  <div class="flex-grow-1 overflow-y-auto">
    <!-- Group: Menu -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Menu</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="index.html" class="sidebar-menu-link active" id="menu-overview" title="Overview">
            <i class="bi bi-grid-fill"></i>
            <span>Admin Dashboard</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Components -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Components</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ route('admin.student_create') }}" class="sidebar-menu-link" id="menu-basictables" title="Basic Tables">
            <i class="bi bi-table"></i>
            <span>Student Register</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="#" class="sidebar-menu-link" id="menu-uiforms" title="Forms and Input">
            <i class="bi bi-input-cursor-text"></i>
            <span>Student List</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="ui-buttons.html" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
            <i class="bi bi-menu-button-wide-fill"></i>
            <span>Lecturer Register</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="ui-buttons.html" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
            <i class="bi bi-menu-button-wide-fill"></i>
            <span>Lecturer List</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="ui-buttons.html" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
            <i class="bi bi-menu-button-wide-fill"></i>
            <span>Module Register</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="ui-buttons.html" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
            <i class="bi bi-menu-button-wide-fill"></i>
            <span>Module List</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Pages -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Pages</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="page-blank.html" class="sidebar-menu-link" id="menu-blankpage" title="Blank Page">
            <i class="bi bi-file-earmark"></i>
            <span>Blank Page</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="page-login.html" class="sidebar-menu-link" id="menu-loginpage" title="Login Page">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Login Screen</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="page-404.html" class="sidebar-menu-link" id="menu-404" title="404 Page">
            <i class="bi bi-slash-circle"></i>
            <span>Error 404</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Sidebar Profile Card (Dynamic Footer) -->
  <div class="sidebar-profile">
    <img src="assets/images/avatar.png" alt="Administrator" class="sidebar-profile-img"
      onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">Administrator</div>
      <div class="sidebar-profile-email">admin@email.com</div>
    </div>
  </div>
</div>
<!-- ==========================================
             END: Sidebar Component
             ========================================== -->


<!-- ==========================================
             START: Main Content Area
             ========================================== -->

<!-- ==========================================
             END: Main Content Area
             ========================================== -->

